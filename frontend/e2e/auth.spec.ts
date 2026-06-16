import { test, expect } from '@playwright/test'
import { ADMIN, login } from './helpers'

// Phase 1 — the auth gate is the cornerstone of the admin-only SPA: every other
// suite depends on login working, and the whole app must be unreachable anonymously.
test.describe('admin auth gate', () => {
  test('anonymous visitor is redirected from the editor to /login', async ({ page }) => {
    await page.goto('/')
    await expect(page).toHaveURL('/login')
    await expect(page.getByTestId('login-submit')).toBeVisible()
  })

  test('invalid credentials show an error and stay on /login', async ({ page }) => {
    await page.goto('/login')
    await page.getByTestId('login-email').fill(ADMIN.email)
    await page.getByTestId('login-password').fill('definitely-wrong')
    await page.getByTestId('login-submit').click()

    await expect(page.getByTestId('login-error')).toBeVisible()
    await expect(page).toHaveURL('/login')
    await expect(page.getByTestId('admin-toolbar')).toHaveCount(0)
  })

  test('valid login lands on the editor with the admin toolbar', async ({ page }) => {
    await login(page)
    await expect(page).toHaveURL('/')
    await expect(page.getByTestId('admin-toolbar')).toContainText(ADMIN.email)
  })

  test('session survives a reload', async ({ page }) => {
    await login(page)
    await page.reload()
    await expect(page.getByTestId('admin-toolbar')).toBeVisible()
    await expect(page).not.toHaveURL('/login')
  })

  test('logout returns to /login and re-gates the editor', async ({ page }) => {
    await login(page)
    await page.getByTestId('admin-logout').click()
    await expect(page).toHaveURL('/login')

    // Gate still holds after logout.
    await page.goto('/')
    await expect(page).toHaveURL('/login')
  })
})
