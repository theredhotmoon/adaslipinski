import { test, expect, type ConsoleMessage } from '@playwright/test'

// Guards against the class of bug where the page server-renders fine but a client
// hydration error (e.g. a teleport-root island) blanks it after load. A plain HTTP
// check can't catch that — this drives a real browser.

for (const path of ['/pl', '/en']) {
  test(`${path} renders and hydrates without console errors`, async ({ page }) => {
    const errors: string[] = []
    page.on('console', (m: ConsoleMessage) => {
      if (m.type() === 'error') errors.push(m.text())
    })
    page.on('pageerror', (e) => errors.push(e.message))

    await page.goto(path, { waitUntil: 'networkidle' })
    await page.waitForTimeout(1500) // allow client:only / client:idle islands to hydrate

    // Not blank: the hero heading is visible and the content has real text. Scope to
    // <main> so the Astro dev toolbar's own headings don't interfere (dev server only).
    await expect(page.locator('main h1')).toBeVisible()
    const textLength = await page.evaluate(() => document.querySelector('main')?.innerText.trim().length ?? 0)
    expect(textLength).toBeGreaterThan(500)

    // No console errors and, crucially, no "Hydration completed but contains mismatches".
    expect(errors, `console errors:\n${errors.join('\n')}`).toEqual([])
  })
}

test('donate modal opens from a trigger (cross-island nanostore)', async ({ page }) => {
  await page.goto('/pl', { waitUntil: 'networkidle' })
  await page.getByRole('button', { name: /Wpłać|Donate/ }).first().click()
  await expect(page.locator('[role="dialog"]')).toBeVisible()
})
