# Admin SPA — Playwright E2E

These specs drive the **authenticated** admin editor, so unlike the public-site
suites in `web/` they need a **live, seeded Laravel backend** reachable at the URL the
SPA uses (`VITE_API_URL`, default `http://localhost:8000/api`).

Feature inventory: `docs/admin-panel.md`. Plan: `docs/admin-panel-e2e-plan.md`.

## Suites

| File | Phase | Covers |
| --- | --- | --- |
| `auth.spec.ts` | 1 | gate redirect, bad creds, login, reload-persists, logout re-gates |
| `inline-edit.spec.ts` | 2 | inline hero edit persists across reload; `Esc` reverts |
| `site-settings.spec.ts` | 3 | section-visibility + layout changes persist |
| `faq-crud.spec.ts` | 4 | add → inline edit → delete an FAQ item |

All mutating specs **restore what they change**, so they're rerunnable against a dev DB.

## Run locally

1. **Start a seeded backend** on `:8000` (admin `admin@example.com` / `password123`):
   ```bash
   cd backend
   php artisan migrate:fresh --seed
   php artisan serve --host=127.0.0.1 --port=8000
   ```
2. **Run the suite** (Playwright starts the SPA dev server itself, or reuses one
   already on `:5173`):
   ```bash
   cd frontend
   npm run test:e2e
   ```

Useful flags: `npm run test:e2e -- --headed` (watch it run),
`--ui` (interactive), `--debug` (step through), `npm run test:e2e -- auth` (one file).

Override the SPA port with `E2E_PORT`, or the API target with `VITE_API_URL`.

## CI

Runs in CI as the **`frontend-e2e`** job (`.github/workflows/ci.yml`): it boots a
seeded PHP backend (like `web-cms-e2e`), installs the chromium browser, and runs
`npm run test:e2e`. Playwright starts the SPA dev server itself in CI
(`reuseExistingServer` is off when `CI` is set). The `deploy-web` job depends on it.
