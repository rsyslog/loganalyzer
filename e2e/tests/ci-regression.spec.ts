/**
 * CI-focused checks + screenshots aligned with changelog-style UX (log view, filters, export,
 * admin display options). Artifacts under e2e/test-results/ci-visual/ upload with playwright-e2e.
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

async function gotoIndexGrid(page: Page): Promise<void> {
  const res = await page.goto('/index.php');
  expect(res?.ok()).toBeTruthy();
  await expect(page.locator('#fullcontenttable')).toBeVisible({ timeout: 60_000 });
  const rows = page.locator('#fullcontenttable tr');
  expect(await rows.count()).toBeGreaterThanOrEqual(2);
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

    const fontSize = page.locator('select[name="DefaultFontSize"]').first();
    await expect(fontSize).toBeVisible({ timeout: 30_000 });
    await expect(fontSize).toHaveValue('100');

    await fontSize.scrollIntoViewIfNeeded();
    await page.screenshot({ path: path.join(ciVisual, '01-admin-full.png'), fullPage: true });

    const fontSection = fontSize.locator('xpath=ancestor::tr[1]');
    await fontSection.screenshot({ path: path.join(ciVisual, '02-admin-font-row.png') });
  });

  test('admin: empty-search default is not bogus placeholder; display toggles visible', async ({
    page,
  }) => {
    await loginAsAdmin(page);
    await page.goto('/admin/index.php');
    await page.waitForLoadState('domcontentloaded');
    await expect(page.locator('body')).not.toContainText(/You need to be logged in/i);

    const emptySearchGlobal = page.locator('input[name="EventEmptySearchDefaultFilter"]').first();
    await expect(emptySearchGlobal).toBeVisible({ timeout: 30_000 });
    const emptyVal = await emptySearchGlobal.inputValue();
    expect(emptyVal.trim().toLowerCase()).not.toBe('{eventemptysearchdefaultfilter}');

    await expect(page.locator('input[name="ViewColoredCells"]').first()).toBeVisible();
    await expect(page.locator('input[name="SuppressDuplicatedMessages"]').first()).toBeVisible();

    const perPage = page.locator('input[name="ViewEntriesPerPage"]').first();
    await expect(perPage).toBeVisible();
    const v = (await perPage.inputValue()).trim();
    expect(v.length).toBeGreaterThan(0);
    const n = Number.parseInt(v, 10);
    expect(Number.isNaN(n)).toBe(false);
    expect(n).toBeGreaterThan(0);

    await page.screenshot({ path: path.join(ciVisual, '05-admin-display-options.png'), fullPage: true });
  });

  test('admin charts: bundled default presets visible', async ({ page }) => {
    await loginAsAdmin(page);
    const res = await page.goto('/admin/charts.php');
    expect(res?.ok()).toBeTruthy();
    await page.waitForLoadState('domcontentloaded');
    await expect(page.locator('body')).not.toContainText(/You need to be logged in/i);
    await expect(page.locator('#chartoptions')).toBeVisible({ timeout: 20_000 });
    await expect(page.locator('#chartoptions').getByText('Top Hosts', { exact: true })).toBeVisible();
    await expect(page.locator('#chartoptions').getByText('Usage by Day', { exact: true })).toBeVisible();
    await page.screenshot({ path: path.join(ciVisual, '12-admin-charts.png'), fullPage: true });
  });

  test('index: syslog grid visible; cell menu opens (screenshot)', async ({ page }) => {
    await loginAsAdmin(page);
    await gotoIndexGrid(page);

    await page.screenshot({ path: path.join(ciVisual, '03-syslog-grid-full.png'), fullPage: true });

    const firstDataRow = page.locator('#fullcontenttable tr').nth(1);
    const menuBtn = firstDataRow.locator('button[id^="button_menu_"]').first();
    await expect(menuBtn).toBeVisible({ timeout: 30_000 });
    await menuBtn.click();

    const cellSearchMenu = firstDataRow.locator('ul.loganalyzer-cell-search-menu');
    await expect(cellSearchMenu.getByText('Available searches', { exact: true }).first()).toBeVisible({
      timeout: 15_000,
    });
    await expect(
      cellSearchMenu.getByRole('link').filter({ hasText: /Filter for ['"].+['"] only/ }).first()
    ).toBeVisible({ timeout: 10_000 });

    await page.screenshot({ path: path.join(ciVisual, '04-cell-search-menu-open.png'), fullPage: true });

    await page.keyboard.press('Escape');
  });

  test('index: structured filter submit keeps grid usable', async ({ page }) => {
    await loginAsAdmin(page);
    await gotoIndexGrid(page);

    const filterBox = page.locator('input[name="filter"]').first();
    // Prefer messagetype (seeded syslog fixtures) — a narrow severity string can yield zero rows or a stream edge case.
    await filterBox.fill('messagetype:Syslog');
    await page.locator('#buttonsearch').click();
    await expect(page).toHaveURL(/filter=/, { timeout: 60_000 });
    await expect(page.locator('#fullcontenttable')).toBeVisible({ timeout: 60_000 });

    await page.screenshot({ path: path.join(ciVisual, '06-index-filter-messagetype.png'), fullPage: true });
  });

  test('plaintext export produces a downloadable .txt', async ({ page }) => {
    await loginAsAdmin(page);
    await gotoIndexGrid(page);

    const exportSelect = page.locator('form[name="exportform"] select[name="exporttype"]');
    await expect(exportSelect).toBeVisible({ timeout: 20_000 });

    const [download] = await Promise.all([
      page.waitForEvent('download', { timeout: 65_000 }),
      exportSelect.selectOption('TXT'),
    ]);
    expect(download.suggestedFilename().toLowerCase()).toMatch(/\.txt$/);
  });

  test('statistics, reports, and advanced search surfaces load without fatal noise', async ({
    page,
  }) => {
    await loginAsAdmin(page);

    for (const { url, shot } of [
      { url: '/statistics.php', shot: '08-statistics.png' },
      { url: '/reports.php', shot: '09-reports.png' },
      { url: '/search.php', shot: '10-advanced-search.png' },
    ] as const) {
      const res = await page.goto(url);
      expect(res?.ok()).toBeTruthy();
      await page.waitForLoadState('domcontentloaded');
      await expect(page.locator('body')).not.toContainText(/Fatal error|Parse error/i);
      await page.screenshot({ path: path.join(ciVisual, shot), fullPage: true });
    }
  });

  test('predefined searches menu when configured', async ({ page }) => {
    await loginAsAdmin(page);
    await page.goto('/index.php');
    await expect(page.locator('#fullcontenttable')).toBeVisible({ timeout: 60_000 });

    const openBtn = page.locator('#openmenu_searches');
    test.skip(
      (await openBtn.count()) === 0,
      'Predefined searches disabled (no $CFG Search in config)'
    );

    await openBtn.click();
    await expect(page.locator('#menu_searches')).toBeVisible({ timeout: 10_000 });
    const entries = page.locator('#menu_searches li a');
    expect(await entries.count()).toBeGreaterThan(0);
    await page.screenshot({ path: path.join(ciVisual, '11-predefined-search-menu.png'), fullPage: true });
    await page.keyboard.press('Escape');
  });
});
