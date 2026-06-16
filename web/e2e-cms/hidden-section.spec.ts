import { test, expect } from '@playwright/test'

// CI flow: a real backend hides `HIDDEN_SECTION` via PUT /admin/settings, the site is
// built against that backend, then this runs against the built output. A control
// section that is NOT hidden must remain — proving selective hiding, not an empty page.
// Section <section id="..."> ids are language-independent, so this works on /pl.
const hidden = process.env.HIDDEN_SECTION ?? 'expenses'
const control = process.env.CONTROL_SECTION ?? 'budget'

test(`hidden section "#${hidden}" is absent while "#${control}" remains`, async ({ page }) => {
  await page.goto('/pl', { waitUntil: 'networkidle' })

  await expect(page.locator('main h1')).toBeVisible()
  await expect(page.locator(`#${control}`)).toHaveCount(1)
  await expect(page.locator(`#${hidden}`)).toHaveCount(0)
})
