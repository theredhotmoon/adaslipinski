# Adaś Lipiński — Open-Source Fundraising Site for Children

A self-hostable fundraising website for children's charities and individual
beneficiaries. It pairs a polished, mobile-first donation page with a built-in,
**inline CMS** — admins edit the live site in place, no separate admin panel.

> Built originally to raise funds for one child; released as a free, reusable
> template so any family, foundation, or volunteer can stand up a trustworthy
> fundraising page in an afternoon.

## ✨ Features

- **Donation page** — hero, story/about, transparent **budget**, **expense
  ledger**, **progress updates**, **milestones**, **tax-deduction info**,
  **foundation** details with bank accounts/links, FAQ, and partners.
- **Inline CMS** — log in as an admin and edit text, numbers, and lists directly
  on the page. Add/remove budget items, expenses, progress posts, FAQ, etc.
- **Responsive** — separate mobile and desktop component sets; desktop ships
  three layout variants (Classic, Editorial, Dashboard).
- **Simple auth** — email-whitelist login (Laravel Passport / JWT). The first
  login with a whitelisted email auto-creates the account.

## 🧱 Tech Stack

| Layer    | Tech                                                            |
| -------- | -------------------------------------------------------------- |
| Frontend | Vue 3 + TypeScript, Vite, Tailwind CSS v4, Pinia, TanStack Query |
| Backend  | Laravel 13, SQLite, Laravel Passport (JWT)                     |
| Tooling  | ESLint, Prettier, Vitest, Playwright, Docker                   |

## 🚀 Quick Start

### Prerequisites
- Node.js 20+ and npm
- PHP 8.3+ and Composer  *(or just Docker for the backend)*

### 1. Backend (Laravel API)

```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan passport:keys        # generates the JWT signing keys (gitignored)

# Allow yourself into the CMS — edit .env:
#   ADMIN_EMAILS=you@example.com
php artisan serve                # http://localhost:8000
```

#### …or run the backend with Docker

```bash
cd backend
cp .env.example .env
php artisan key:generate         # writes APP_KEY into .env (read by compose)
# set ADMIN_EMAILS in .env, then:
docker compose up --build        # http://localhost:8000
```

> ⚠️ **The container bakes the app code at build time** (only the database and
> `storage/` are volume-mounted). After **any backend code change**, rebuild or
> the container keeps running the old code:
> ```bash
> docker compose up -d --build
> ```
> Symptom of a stale container: API responses show raw `{"pl":…,"en":…}` JSON
> (old code without the translation cast reading new bilingual data). For live
> code reload during development, run `php artisan serve` on the host instead.

### 2. Frontend (Vue app)

```bash
cd frontend
cp .env.example .env             # defaults point at http://localhost:8000/api
npm install
npm run dev                      # http://localhost:5173
```

### 3. Log in & edit
Open the site, click the admin bar (bottom-right), and log in with an email
listed in `ADMIN_EMAILS`. Your first login sets that account's password.

## ⚙️ Configuration

| Variable                | Where            | Purpose                                              |
| ----------------------- | ---------------- | ---------------------------------------------------- |
| `ADMIN_EMAILS`          | backend `.env`   | Comma-separated admin allowlist (auto-creates on 1st login) |
| `FRONTEND_URL`          | backend `.env`   | Frontend origin; default CORS allow-origin           |
| `CORS_ALLOWED_ORIGINS`  | backend `.env`   | Override CORS origins (comma-separated; `*` for all) |
| `APP_KEY`               | backend `.env`   | Laravel encryption key (`php artisan key:generate`)  |
| `VITE_API_URL`          | frontend `.env`  | Base URL of the API (e.g. `https://api.example.com/api`) |
| `TOLGEE_API_KEY`        | shell env        | Tolgee Project API Key for translation sync (optional)   |

## 🌍 Translations (Tolgee)

UI strings live in `frontend/src/i18n/locales/{pl,en}.json`. You can edit those
files directly, or manage translations in [Tolgee](https://tolgee.io) (free tier
= 1,000 strings) and sync via the CLI. One-time setup:

1. Create a free project at [app.tolgee.io](https://app.tolgee.io) and generate a
   **Project API Key** (PAK).
2. Put your project ID in `frontend/.tolgeerc.json` (`"projectId"`), and export
   the key: `export TOLGEE_API_KEY=tgpak_…` (PowerShell: `$env:TOLGEE_API_KEY=…`).
3. Seed Tolgee with the existing keys: `cd frontend && npm run i18n:push:seed`.

Day-to-day (from `frontend/`):

```bash
npm run i18n:pull      # Tolgee → local JSON (after translating in the UI)
npm run i18n:push      # local JSON → Tolgee (adds new keys, keeps existing)
npm run i18n:compare   # diff local code/keys against the Tolgee project
```

**MCP:** `.mcp.json` registers the official Tolgee MCP server (HTTP), so Claude
can search/update translations directly — it reads `TOLGEE_API_KEY` from your env.

> Note: the locale files use Vue I18n `{name}`-style placeholders; `.tolgeerc.json`
> is set to `JSON_ICU`. Verify the first round-trip preserves placeholders/markup
> and adjust `format` if your project needs a different mapping.

## 🔒 Security notes

- **Never commit** `.env`, `database/*.sqlite`, or `storage/*.key` — they are
  gitignored. They hold secrets, real data, and the JWT signing keys.
- Generate a **fresh `APP_KEY`** and **Passport keys** per deployment.
- CORS defaults to your `FRONTEND_URL`, not `*`. Widen it only deliberately.
- Use a **strong password** on first admin login — that login sets it
  permanently for that email.
- Found a vulnerability? Please open a private security advisory rather than a
  public issue.

## 🤝 Contributing & reuse

This is meant to be forked. To make it *your* project:
1. Replace the beneficiary story/content (it's all editable via the inline CMS,
   or seed your own in `backend/database/seeders`).
2. Swap branding, colors (theme uses CSS `oklch` variables), and the hero image.
3. Set your own `ADMIN_EMAILS`, foundation, and bank details.

PRs welcome — especially deployment guides, translations, and accessibility
improvements. See `CLAUDE.md` for project conventions.

## 📄 License

[MIT](LICENSE) © 2026 Kuba Urbanczyk. Free to use, modify, and redistribute —
including for other children and causes. ❤️
