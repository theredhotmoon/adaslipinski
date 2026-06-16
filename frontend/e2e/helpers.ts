import { expect, type Page, type Locator } from '@playwright/test'

/** Seeded admin (see backend DatabaseSeeder / CI). */
export const ADMIN = { email: 'admin@example.com', password: 'password123' }

/** Log in through the UI and land on the editor with the admin toolbar showing. */
export async function login(page: Page): Promise<void> {
  await page.goto('/login')
  await page.getByTestId('login-email').fill(ADMIN.email)
  await page.getByTestId('login-password').fill(ADMIN.password)
  await Promise.all([
    page.waitForResponse(
      (r) => r.url().includes('/auth/login') && r.request().method() === 'POST',
    ),
    page.getByTestId('login-submit').click(),
  ])
  await expect(page.getByTestId('admin-toolbar')).toBeVisible()
}

/**
 * Replace the text of an inline-editable field and wait for the resulting save.
 * `InlineText` commits on blur, so we fill then blur and assert the write lands.
 */
export async function editInline(
  page: Page,
  field: Locator,
  text: string,
  urlPart: string,
  method = 'PATCH',
): Promise<void> {
  await field.scrollIntoViewIfNeeded()
  await field.click()
  await field.fill(text)
  const [res] = await Promise.all([
    page.waitForResponse(
      (r) => r.url().includes(urlPart) && r.request().method() === method,
    ),
    field.blur(),
  ])
  expect(res.ok(), `${method} ${urlPart} should succeed`).toBeTruthy()
}
