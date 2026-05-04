import { test, expect } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

/** Repo root contains `doc-site/` and `e2e/`. */
const repoRoot = path.join(__dirname, '..', '..');
const handbookAssets =
  process.env.HANDBOOK_USER_GUIDE_ASSETS != null &&
  process.env.HANDBOOK_USER_GUIDE_ASSETS !== ''
    ? path.resolve(process.env.HANDBOOK_USER_GUIDE_ASSETS)
    : path.join(repoRoot, 'doc-site', 'docs', 'assets', 'user-guide');

const adminUser = process.env.LOGANALYZER_ADMIN_USER ?? 'admin';
const adminPass = process.env.LOGANALYZER_ADMIN_PASSWORD ?? 'loganalyzer';

/** After login submit, wait for redirect to main view (PHP may be slow in Docker CI; keep below test timeout). */
const LOGIN_REDIRECT_TIMEOUT_MS = 60_000;

test('capture handbook screenshots under doc-site/docs/assets/user-guide', async ({ page }) => {
  fs.mkdirSync(handbookAssets, { recursive: true });

  let res = await page.goto('/index.php');
  expect(res?.ok()).toBeTruthy();
  await page.locator('body').waitFor({ state: 'visible' });
  await page.screenshot({ path: path.join(handbookAssets, 'index.png'), fullPage: true });

  res = await page.goto('/login.php');
  expect(res?.ok()).toBeTruthy();
  await page.screenshot({ path: path.join(handbookAssets, 'login.png'), fullPage: true });

  await page.locator('input[name="uname"]').fill(adminUser);
  await page.locator('input[name="pass"]').fill(adminPass);
  await Promise.all([
    page.waitForURL(/\/index\.php(\?|$)/i, { timeout: LOGIN_REDIRECT_TIMEOUT_MS }),
    page.locator('input[type="submit"]').click(),
  ]);
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('body')).not.toContainText(/Wrong username or password/i);

  res = await page.goto('/admin/index.php');
  expect(res?.ok()).toBeTruthy();
  await page.waitForLoadState('domcontentloaded');
  await expect(page.locator('body')).not.toContainText(/You need to be logged in/i);
  await expect(page.locator('select[name="ViewDefaultTheme"]').first()).toBeVisible({
    timeout: 30_000,
  });

  await page.screenshot({ path: path.join(handbookAssets, 'admin.png'), fullPage: true });

  res = await page.goto('/statistics.php');
  expect(res?.ok()).toBeTruthy();
  await page.screenshot({ path: path.join(handbookAssets, 'statistics.png'), fullPage: true });

  res = await page.goto('/reports.php');
  expect(res?.ok()).toBeTruthy();
  await page.screenshot({ path: path.join(handbookAssets, 'reports.png'), fullPage: true });
});
