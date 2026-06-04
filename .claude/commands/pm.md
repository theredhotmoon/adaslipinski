# Project Manager — Status & Planning

Act as the project manager for **Adaś Lipiński** (a fundraising / charity microsite). Your job is to answer "where are we with the project?" accurately at any point in time by **inspecting the live repository first**, then reporting against the baseline below. Never answer from the baseline alone — it is a point-in-time reference that drifts. Verify before you assert.

## How to answer (run these every time)

1. **Git state** — recent history, working tree, branch:
   - `git log --oneline -15`, `git status --short`, `git branch --show-current`
2. **Frontend inventory** — what features/screens/components exist:
   - Glob `frontend/src/features/**/*.{vue,ts}`, `frontend/src/pages/*.vue`, `frontend/src/router/index.ts`
3. **Backend inventory** — API surface and data model:
   - Read `backend/routes/api.php`; Glob `backend/app/Models/*.php` and `backend/database/migrations/*.php`
4. **Open work** — search for unfinished markers:
   - Grep for `TODO|FIXME|HACK|XXX|@todo` across `frontend/src` and `backend/app`
5. **Project memory** — read `MEMORY.md` and the `project_*.md` files in the memory dir for decisions/gotchas.

Then synthesize — do not dump raw output.

## Report format

Produce a concise status report with these sections:

- **TL;DR** — one or two sentences: overall phase and momentum.
- **✅ Done** — shipped/working features (frontend screens, backend endpoints, infra).
- **🚧 In progress / uncommitted** — what's in the working tree but not committed, what's half-built.
- **⏭️ Next up** — the logical next steps, ordered.
- **⚠️ Risks & gotchas** — blockers, tech debt, known traps (pull from memory + code).
- **▶️ How to run** — frontend (`cd frontend && npm run dev`) and backend (Laravel `php artisan serve` / docker-compose) commands, verified against the actual scripts in `package.json` / `composer.json`.

Keep it skimmable. Use the user's own domain language (donations, budget, expenses, progress, foundation, tax, milestones).

## Baseline (as of 2026-06-05 — VERIFY, do not trust blindly)

**Stack**: Vue 3 + TS + Vite + Tailwind v4 frontend; Laravel 13 + SQLite + Passport (JWT) backend. TanStack Vue Query for data, Pinia for auth. See `CLAUDE.md`.

**Shape of the build at baseline:**
- *Frontend* — single public route `/` (`FundraisingPage`) plus `/login` and `/change-password`. Mobile (`Fr*`) and desktop (`D*`) component sets under `features/fundraising/`, with 8 screens: Home, About, Budget, Progress, Expenses, Tax, Foundation, Contact. Desktop has 3 alternative layouts (Classic, Editorial, Dashboard). Inline CMS editing lives in `features/admin/`.
- *Backend* — full CMS admin API in `routes/api.php`: auth (login/me/change-password), public `GET /cms/site`, and admin CRUD for beneficiary, foundation (+accounts/links), budget-items, progress, milestones, expenses, faq, partners, donation-amounts, testimonials, year-summaries, media, config. 16 Eloquent models.
- *Git* — repo had only ONE commit (the Vue scaffold); the entire frontend rebuild + whole backend were **uncommitted/untracked** on branch `master`. Flag committing as a likely next step if still true.
- *Key gotcha* — admin email whitelist via `ADMIN_EMAILS` (must be set in both `.env` and `docker-compose.yml`); auto-creates the user on first login. PowerShell mangles Polish UTF-8 chars when testing edits — test via browser/tinker. See memory `project_cms_admin.md`.
- *Source brief* — `Adas_research.docx` at repo root is the original research/requirements doc; consult it for scope questions about what the site is meant to contain.

If the user passes a focus area, scope the report to it: $ARGUMENTS
