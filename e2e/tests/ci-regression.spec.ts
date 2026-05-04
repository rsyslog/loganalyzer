/**
 * CI-focused checks + screenshots for regressions (admin options, log grid, cell search menus).
 * Artifacts: e2e/test-results/ci-visual/*.png (included in GitHub Actions upload).
 */
import * as fs from 'fs';
import * as path from 'path';
import { expect, test, type Page } from '@playwright/test';

const adminUser = process.env.LOGANALYZER_ADMIN_USER ?? 'admin';
const adminPass = process.env.LOGANALYZER_ADMIN_PASSWORD ?? 'loganalyzer';
const LOGIN_REDIRECT_TIMEOUT_MS = 60_000;

const ciVisual = path.join(__dirname, '..', 'test-results', 'ci-visual');

async function loginAsAdmin(page: Page): Promise<void> {
  await page.goto('/login.php');
  await page.locator('input[name="uname"]').fill(adminUser);
  await page.locator('input[name="pass"]').fill(adminPass);
  await Promise.all([
    page.waitForURL(/\/index\.php(\?|$)/i, { timeout: LOGIN_REDIRECT_TIMEOUT_MS }),
    page.locator('input[type="submit"]').click(),
  ]);
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('body')).not.toContainText(/Wrong username or password/i);
}

test.describe('CI regression (admin + log views)', () => {
  test.beforeAll(() => {
    fs.mkdirSync(ciVisual, { recursive: true });
  });

  test('admin index: font controls load; DefaultFontSize normalized to 100', async ({ page }) => {
    await loginAsAdmin(page);
    const res = await page.goto('/admin/index.php');
    expect(res?.ok()).toBeTruthy();
    await page.waitForLoadState('domcontentloaded');
    await expect(page.locator('body')).not.toContainText(/You need to be logged in/i);

    const fontSize = page.locator('select#DefaultFontSize');
    await expect(fontSize).toBeVisible({ timeout: 30_000 });
    await expect(fontSize).toHaveValue('100');

    await fontSize.scrollIntoViewIfNeeded();
    await page.screenshot({ path: path.join(ciVisual, '01-admin-full.png'), fullPage: true });

    const fontSection = page.locator('tr').filter({ has: fontSize });
    await fontSection.screenshot({ path: path.join(ciVisual, '02-admin-font-row.png') });
  });

  test('index: syslog grid visible; cell menu opens (screenshot)', async ({ page }) => {
    await loginAsAdmin(page);
    const res = await page.goto('/index.php');
    expect(res?.ok()).toBeTruthy();

    const grid = page.locator('#fullcontenttable');
    await expect(grid).toBeVisible({ timeout: 60_000 });

    const rows = grid.locator('tr');
    expect(await rows.count()).toBeGreaterThanOrEqual(2);

    await page.screenshot({ path: path.join(ciVisual, '03-syslog-grid-full.png'), fullPage: true });

    const firstDataRow = rows.nth(1);
    await expect(firstDataRow).toBeVisible();

    const menuBtn = firstDataRow.locator('button[id^="button_menu_"]').first();
    await expect(menuBtn).toBeVisible({ timeout: 30_000 });
    await menuBtn.click();

    await expect(page.getByText('Available searches', { exact: true }).first()).toBeVisible({
      timeout: 15_000,
    });
    await expect(page.getByText(/Filter for ['"].*['"] only/)).toBeVisible({ timeout: 10_000 });

    await page.screenshot({ path: path.join(ciVisual, '04-cell-search-menu-open.png'), fullPage: true });

    await page.keyboard.press('Escape');
  });
});
