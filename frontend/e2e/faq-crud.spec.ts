import { test, expect } from '@playwright/test'
import { login } from './helpers'

// Phase 4 — a full create→update→delete round-trip for one collection (FAQ), which
// exercises the add modal, inline editing within a list, and the confirm-guarded
// delete. The test adds a uniquely-named item and removes it, so it nets to zero.
test.describe.serial('FAQ CRUD', () => {
  test('add, edit, then delete an FAQ item', async ({ page }) => {
    await login(page)

    const question = `E2E pytanie ${Date.now()}`
    const answer = 'E2E odpowiedź testowa.'

    // ── Create ──────────────────────────────────────────────────────────────
    await page.getByTestId('faq-add').scrollIntoViewIfNeeded()
    await page.getByTestId('faq-add').click()
    await expect(page.getByTestId('admin-modal')).toBeVisible()
    await page.getByTestId('faq-input-question').fill(question)
    await page.getByTestId('faq-input-answer').fill(answer)
    await Promise.all([
      page.waitForResponse(
        (r) => r.url().includes('/admin/faq') && r.request().method() === 'POST',
      ),
      page.getByTestId('admin-modal-save').click(),
    ])

    const row = page.getByTestId('faq-row').filter({ hasText: question })
    await expect(row).toBeVisible()

    // ── Update (inline edit of the question) ────────────────────────────────
    const editedQuestion = `${question} (edytowane)`
    const editable = row.locator('[contenteditable="true"]').first()
    await editable.click()
    await editable.fill(editedQuestion)
    await Promise.all([
      page.waitForResponse(
        (r) => r.url().includes('/admin/faq') && r.request().method() === 'PUT',
      ),
      editable.blur(),
    ])

    await page.reload()
    await expect(page.getByTestId('faq-row').filter({ hasText: editedQuestion })).toBeVisible()

    // ── Delete (native confirm → auto-accept) ───────────────────────────────
    page.once('dialog', (d) => d.accept())
    const editedRow = page.getByTestId('faq-row').filter({ hasText: editedQuestion })
    await Promise.all([
      page.waitForResponse(
        (r) => r.url().includes('/admin/faq') && r.request().method() === 'DELETE',
      ),
      editedRow.getByRole('button', { name: /usuń|delete/i }).click(),
    ])

    await expect(page.getByTestId('faq-row').filter({ hasText: editedQuestion })).toHaveCount(0)

    // And it stays gone after a reload.
    await page.reload()
    await expect(page.getByTestId('faq-row').filter({ hasText: question })).toHaveCount(0)
  })
})
