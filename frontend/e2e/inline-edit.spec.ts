import { test, expect } from '@playwright/test'
import { login, editInline } from './helpers'

// Phase 2 — the core editing round-trip: an inline edit must reach the API and
// survive a reload (proving it was persisted, not just optimistically shown).
// Each test restores the original value so the suite is rerunnable against a dev DB.
test.describe.serial('inline editing', () => {
  test('editing the hero title persists across a reload', async ({ page }) => {
    await login(page)
    const hero = page.getByTestId('edit-hero-title')
    await expect(hero).toBeVisible()

    const original = (await hero.innerText()).trim()
    const updated = `${original} ✎${Date.now() % 100000}`

    await editInline(page, hero, updated, '/admin/beneficiary')
    await expect(hero).toHaveText(updated)

    // Reload reads fresh from the CMS API — the edit is gone unless it was saved.
    await page.reload()
    await expect(page.getByTestId('edit-hero-title')).toHaveText(updated)

    // Restore the original value.
    await editInline(page, page.getByTestId('edit-hero-title'), original, '/admin/beneficiary')
    await page.reload()
    await expect(page.getByTestId('edit-hero-title')).toHaveText(original)
  })

  test('Escape reverts an in-progress edit without saving', async ({ page }) => {
    await login(page)
    const hero = page.getByTestId('edit-hero-title')
    const original = (await hero.innerText()).trim()

    await hero.click()
    await hero.fill('temporary junk that must not be saved')
    await hero.press('Escape')

    await expect(hero).toHaveText(original)

    // And a reload confirms nothing was written.
    await page.reload()
    await expect(page.getByTestId('edit-hero-title')).toHaveText(original)
  })
})
