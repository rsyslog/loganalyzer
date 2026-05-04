import { test, expect } from '@playwright/test';
import * as fs from 'fs';
import * as path from 'path';

const shots = path.join(__dirname, '..', 'test-results', 'screenshots');

const adminUser = process.env.LOGANALYZER_ADMIN_USER ?? 'admin';
const adminPass = process.env.LOGANALYZER_ADMIN_PASSWORD ?? 'pass';

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
  await page.locator('input[type="submit"]').click();
  await page.waitForLoadState('networkidle');

  res = await page.goto('/admin/index.php');
  expect(res?.ok()).toBeTruthy();
  body = await page.locator('body').innerText();
  expect(body).toMatch(/admin|LogAnalyzer|Sources/i);
  await page.screenshot({ path: path.join(shots, '02-admin.png'), fullPage: true });

  await page.goto('/statistics.php');
  await page.screenshot({ path: path.join(shots, '03-statistics.png'), fullPage: true });

  await page.goto('/reports.php');
  await page.screenshot({ path: path.join(shots, '04-reports.png'), fullPage: true });
});
