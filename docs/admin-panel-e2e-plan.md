# Admin Panel — E2E Test Plan (proposal)

Status: **implemented (Phases 1–4), wired into CI.** Suites live in `frontend/e2e/`; run
steps in `frontend/e2e/README.md`. All 10 tests pass against a seeded backend. Feature
inventory lives in `docs/admin-panel.md`.

Decisions (2026-06-16): shipped Phases 1–4 (skipped optional media upload); added the
`frontend-e2e` CI job (seeded backend, mirrors `web-cms-e2e`); added `data-testid` hooks
for stable selectors.

## Why e2e (vs. the existing Vitest specs)

The admin SPA's value is the full round-trip: log in → edit inline / via modal → write
to `/api/admin/*` → reload → change persisted → public build reflects it. Vitest (jsdom,
mocked API) can't exercise that. We already drive a real browser + real backend for the
public site (`web/e2e-cms`); this mirrors that pattern for the admin app.

## Harness (new — none exists for `frontend/` yet)

1. Swap the stray `playwright` dep for `@playwright/test`; add `frontend/playwright.config.ts`
   (`testDir: ./e2e`, chromium, `webServer: npm run preview`, reuse-existing locally).
2. Add `frontend/e2e/` and an `e2e` fixtures dir (a small image for upload tests).
3. Add npm script `test:e2e`.
4. **Backend dependency:** admin actions require auth + `/api/admin/*`, so CI must run a
   seeded Laravel backend and build/serve the SPA against it — same recipe as the
   `web-cms-e2e` job (seed + `passport:client --personal` + `php artisan serve`), with the
   SPA built with `VITE_API_URL=http://127.0.0.1:8000/api`.
5. `frontend-e2e` CI job (Node 20 + seeded PHP backend, mirrors `web-cms-e2e`); the
   `deploy-web` job depends on it. Local run steps documented in `frontend/e2e/README.md`.

## Test data strategy

Mutating tests must not leave the DB dirty for the next run. Proposed:
- **CI:** `migrate:fresh --seed` immediately before the suite → known baseline.
- **Local:** each mutating test **restores** the value it changed (edit → assert → set back),
  so `npm run test:e2e` is rerunnable against a dev DB without corrupting it.

## Suites (in priority order)

### Phase 1 — Auth gate & session (highest signal, lowest setup)
Only needs the seeded admin user; no content assertions.
- Anonymous visit to `/` → redirected to `/login`.
- Invalid credentials → inline error, stays on `/login`.
- Valid login → lands on editor; "Admin mode" toolbar + signed-in email visible.
- Reload after login → still authenticated (token persists), not bounced to `/login`.
- Logout → back to `/login`; re-visiting `/` stays gated.

### Phase 2 — Inline editing persistence
- Log in, edit a hero/heading field via `InlineText` (focus → type → blur).
- Assert optimistic update in the preview, then **reload** → value persisted
  (`PATCH /admin/beneficiary` round-trip). Restore original.
- `Esc` reverts an in-progress edit without saving.

### Phase 3 — Site settings (admin control side)
Complements `web-cms-e2e`, which checks the public *effect*; this checks the *control*.
- Open settings, toggle a section to hidden, **Save**; reopen → checkbox reflects hidden.
- Change layout `classic → editorial`, Save, reload → persisted (`GET /admin/settings`).
- Restore defaults afterward.

### Phase 4 — One CRUD collection end-to-end (e.g. FAQ)
- Add an FAQ item → appears in the list.
- Edit its text → persists across reload.
- Delete it → gone (confirm dialog path). Leaves the DB as found.

### Phase 5 (optional) — Media upload — NOT IMPLEMENTED
- Upload a fixture image via `AdminImageUpload` → assert returned URL renders.
  Heavier (storage, cleanup); deferred. A future addition if media coverage is wanted.

## Resolved decisions

1. **Scope** — shipped Phases 1–4 (Phase 5 deferred).
2. **CI backend** — added the `frontend-e2e` job (seeded backend, mirrors `web-cms-e2e`).
3. **Selectors** — added `data-testid` (and `aria-pressed` on layout buttons) hooks.
