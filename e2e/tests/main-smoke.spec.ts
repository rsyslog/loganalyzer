import { test, expect } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

const shots = path.join(__dirname, '..', 'test-results', 'screenshots');

const adminUser = process.env.LOGANALYZER_ADMIN_USER ?? 'admin';
const adminPass = process.env.LOGANALYZER_ADMIN_PASSWORD ?? 'loganalyzer';

/** After login submit, wait for redirect to main view (PHP may be slow in Docker CI; keep below test timeout). */
const LOGIN_REDIRECT_TIMEOUT_MS = 60_000;

test('main flows with screenshots (session kept in one test)', async ({ page }) => {
  fs.mkdirSync(shots, { recursive: true });

  let res = await page.goto('/index.php');
  expect(res?.ok()).toBeTruthy();
  let body = await page.locator('body').innerText();
  expect(body.length).toBeGreaterThan(50);
  await page.screenshot({ path: path.join(shots, '01-index.png'), fullPage: true });

  await page.goto('/login.php');
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

  body = await page.locator('body').innerText();
  expect(body).not.toMatch(/Wrong username or password/i);
  expect(body).not.toMatch(/You need to be logged in/i);
  await page.screenshot({ path: path.join(shots, '02-admin.png'), fullPage: true });

  await page.goto('/statistics.php');
  await page.screenshot({ path: path.join(shots, '03-statistics.png'), fullPage: true });

  await page.goto('/reports.php');
  await page.screenshot({ path: path.join(shots, '04-reports.png'), fullPage: true });
});
