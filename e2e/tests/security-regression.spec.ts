import { expect, test, type Page } from '@playwright/test';

const adminUser = process.env.LOGANALYZER_ADMIN_USER ?? 'admin';
const adminPass = process.env.LOGANALYZER_ADMIN_PASSWORD ?? 'loganalyzer';
const loginTimeout = 60_000;
const marker = `e2e-${Date.now()}`;
const normalUser = `${marker}-user`;
const normalPass = 'loganalyzer-e2e-pass';
const storedName = `${marker} <script>window.loganalyzerXss=1</script>`;
const storedDescription = `${marker} <img src=x onerror=window.loganalyzerXss=2>`;

async function login(page: Page, username: string, password: string): Promise<void> {
  await page.goto('/login.php');
  await page.locator('input[name="uname"]').fill(username);
  await page.locator('input[name="pass"]').fill(password);
  await Promise.all([
    page.waitForURL(/\/index\.php(\?|$)/i, { timeout: loginTimeout }),
    page.locator('input[type="submit"]').click(),
  ]);
  await expect(page.locator('body')).not.toContainText(/Wrong username or password/i);
}

async function createNormalUser(page: Page): Promise<void> {
  await login(page, adminUser, adminPass);
  await page.goto('/admin/users.php?op=add');
  await page.locator('input[name="username"]').fill(normalUser);
  await page.locator('input[name="password1"]').fill(normalPass);
  await page.locator('input[name="password2"]').fill(normalPass);
  await page.locator('form[action*="users.php"] input[type="submit"]').click();
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('body')).not.toContainText(/SQL syntax|Fatal error|Parse error/i);
}

async function submitDiskSource(page: Page, name: string, description: string, filePath: string): Promise<void> {
  await page.goto('/admin/sources.php?op=add');
  await page.locator('input[name="Name"]').fill(name);
  await page.locator('textarea[name="Description"]').fill(description);
  await page.locator('select[name="SourceType"]').selectOption('1');
  await page.locator('select[name="SourceViewID"]').selectOption('SYSLOG');
  await page.locator('select[name="SourceLogLineType"]').selectOption('syslog');
  await page.locator('input[name="SourceDiskFile"]').fill(filePath);
  await page.locator('form[action*="sources.php"] input[type="submit"]').click();
  await page.waitForLoadState('domcontentloaded');
}

test.describe('security remediation regressions', () => {
  test.describe.configure({ mode: 'serial' });

  test.beforeAll(async ({ browser }) => {
    const page = await browser.newPage();
    await createNormalUser(page);
    await page.close();
  });

  test('normal authenticated chart requests reject injected order expressions', async ({ page }) => {
    await login(page, normalUser, normalPass);
    const response = await page.request.get(
      '/chartgenerator.php?type=1&byfield=FROMHOST&orderby=totalcount%20DESC%2C%20SLEEP%281%29&width=300&maxrecords=10&showpercent=0&basepath=./'
    );
    expect(response.status()).toBeLessThan(500);
    expect(await response.text()).not.toMatch(/SQL syntax|Unknown column|You have an error/i);
  });

  test('administrator mapping writes treat display names as values', async ({ page }) => {
    await login(page, adminUser, adminPass);
    const response = await page.request.post('/admin/dbmappings.php', {
      form: {
        op: 'addnewdbmp',
        DisplayName: `${marker} <script>window.mappingXss=1</script>`,
        'Mappings[]': 'ID',
        ID: 'ID',
      },
    });
    expect(response.status()).toBeLessThan(500);
    await page.goto('/admin/dbmappings.php');
    await expect(page.locator('body')).toContainText(`${marker} <script>window.mappingXss=1</script>`);
    await expect(page.locator('script').filter({ hasText: 'mappingXss' })).toHaveCount(0);
  });

  test('reflected result messages and redirect targets stay inert', async ({ page }) => {
    await login(page, normalUser, normalPass);
    const message = `${marker} <script>window.resultXss=1</script>`;
    await page.goto(`/admin/result.php?msg=${encodeURIComponent(message)}&redir=javascript%3Aalert%281%29`);
    await expect(page.locator('body')).toContainText(message);
    await expect(page.locator('script').filter({ hasText: 'resultXss' })).toHaveCount(0);
    const refresh = page.locator('meta[http-equiv="REFRESH"]');
    await expect(refresh).toHaveAttribute('content', /URL=index\.php/i);
  });

  test('normal-user source output is escaped for the creator and administrator', async ({ browser }) => {
    const normalContext = await browser.newContext();
    const normalPage = await normalContext.newPage();
    await login(normalPage, normalUser, normalPass);
    await submitDiskSource(normalPage, storedName, storedDescription, '/samplelogs/sampledata_syslog.log');

    await normalPage.goto('/index.php');
    const sourceOption = normalPage.locator('select[name="sourceid"] option').filter({ hasText: marker });
    await expect(sourceOption).toHaveCount(1);
    await expect(normalPage.locator('script').filter({ hasText: 'loganalyzerXss' })).toHaveCount(0);
    await normalContext.close();

    const oraclePage = await browser.newPage();
    await login(oraclePage, normalUser, normalPass);
    await oraclePage.goto(`/asktheoracle.php?type=searchstr&query=${encodeURIComponent(marker)}`);
    await expect(oraclePage.locator('body')).toContainText(storedName);
    await expect(oraclePage.locator('script').filter({ hasText: 'loganalyzerXss' })).toHaveCount(0);
    await oraclePage.close();

    const adminPage = await browser.newPage();
    await login(adminPage, adminUser, adminPass);
    await adminPage.goto('/admin/sources.php');
    const sourceRow = adminPage.locator('tr').filter({ hasText: storedName }).last();
    await expect(sourceRow).toContainText(storedName);
    await expect(adminPage.locator('script').filter({ hasText: 'loganalyzerXss' })).toHaveCount(0);

    const editHref = await sourceRow.locator('a[href*="op=edit"]').first().getAttribute('href');
    const sourceId = new URL(editHref!, 'http://localhost/').searchParams.get('id');
    await adminPage.goto(`/admin/reports.php?sourceid=${sourceId}`);
    await expect(adminPage.locator('script').filter({ hasText: 'loganalyzerXss' })).toHaveCount(0);
  });

  test('deletion confirmation and error output escape stored source values', async ({ page }) => {
    await login(page, adminUser, adminPass);
    await page.goto('/admin/sources.php');
    const row = page.locator('tr').filter({ hasText: storedName }).last();
    const deleteHref = await row.locator('a[href*="op=delete"]').getAttribute('href');
    expect(deleteHref).toMatch(/id=\d+/);
    await page.goto(deleteHref!);
    await expect(page.locator('body')).toContainText(storedName);
    await expect(page.locator('script').filter({ hasText: 'loganalyzerXss' })).toHaveCount(0);
  });

  test('LFI traversal and symlink escapes are rejected before file reads', async ({ page }) => {
    await login(page, normalUser, normalPass);

    await submitDiskSource(page, `${marker}-traversal`, 'traversal', '/var/log/../../etc/passwd');
    await expect(page.locator('body')).not.toContainText(/root:x:0:0:/);
    await expect(page.locator('body')).toContainText(/not allowed|not found|error/i);

    await submitDiskSource(page, `${marker}-symlink`, 'symlink', '/tmp/loganalyzer-e2e-allowed/passwd-link');
    await expect(page.locator('body')).not.toContainText(/root:x:0:0:/);

    const futureName = `${marker}-future-file`;
    await submitDiskSource(page, futureName, 'future file', '/tmp/loganalyzer-e2e-allowed/future.log');
    await expect(page.locator('body')).toContainText(futureName);
    await expect(page.locator('body')).not.toContainText(/path not allowed|error within source/i);
  });
});
