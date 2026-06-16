import { test, expect, type Page } from '@playwright/test'
import { login } from './helpers'

// Phase 3 — the admin *control* over the public site. web/e2e-cms asserts the public
// *effect* of hiding a section; here we assert the panel itself persists layout and
// section-visibility changes. Both tests restore what they changed.

async function openSettings(page: Page) {
  await page.getByTestId('admin-settings-btn').click()
  await expect(page.getByTestId('admin-modal')).toBeVisible()
}

async function saveSettings(page: Page) {
  await Promise.all([
    page.waitForResponse(
      (r) => r.url().includes('/admin/settings') && r.request().method() === 'PUT',
    ),
    page.getByTestId('admin-modal-save').click(),
  ])
  await expect(page.getByTestId('admin-modal')).toHaveCount(0)
}

test.describe.serial('site settings', () => {
  test('toggling a section visibility persists', async ({ page }) => {
    await login(page)
    await openSettings(page)

    const checkbox = page.locator('[data-testid^="settings-section-"]').first()
    const testid = await checkbox.getAttribute('data-testid')
    const wasChecked = await checkbox.isChecked()

    await checkbox.click()
    await saveSettings(page)

    // Reopen — the staged change should have been committed and reloaded.
    await openSettings(page)
    const reopened = page.locator(`[data-testid="${testid}"]`)
    expect(await reopened.isChecked()).toBe(!wasChecked)

    // Restore.
    await reopened.click()
    await saveSettings(page)
  })

  test('changing the desktop layout persists across a reload', async ({ page }) => {
    await login(page)
    await openSettings(page)

    const layouts = page.locator('[data-testid^="settings-layout-"]')
    const count = await layouts.count()
    expect(count).toBeGreaterThan(1)

    const current = page.locator('[data-testid^="settings-layout-"][aria-pressed="true"]')
    const originalId = await current.first().getAttribute('data-testid')

    // Pick any layout that isn't the current one.
    let targetId: string | null = null
    for (let i = 0; i < count; i++) {
      const id = await layouts.nth(i).getAttribute('data-testid')
      if (id && id !== originalId) { targetId = id; break }
    }
    expect(targetId).toBeTruthy()

    await page.locator(`[data-testid="${targetId}"]`).click()
    await saveSettings(page)

    await page.reload()
    await openSettings(page)
    await expect(page.locator(`[data-testid="${targetId}"]`)).toHaveAttribute('aria-pressed', 'true')

    // Restore the original layout.
    await page.locator(`[data-testid="${originalId}"]`).click()
    await saveSettings(page)
  })
})
