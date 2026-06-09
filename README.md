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

UI strings live in `frontend/src/i18n/locales/{pl,en}.json` — **edit those files
directly and you never need Tolgee at all.** Tolgee is an *optional* translation
manager (web UI, machine translation, translator collaboration) that syncs to and
from those same files. This repo ships the integration pre-wired; you just add a
project and a token.

### Installation

The [Tolgee CLI](https://docs.tolgee.io/tolgee-cli) is already a dev dependency
(`@tolgee/cli`), so a normal `cd frontend && npm install` is all you need. Config
lives in `frontend/.tolgeerc.json` (Tolgee Cloud, `JSON_ICU` format, pushing/
pulling `pl.json` + `en.json`). For a fresh install elsewhere:

```bash
npm install -D @tolgee/cli      # already in package.json here
```

### The API token

The CLI and the MCP server authenticate with a Tolgee **API token**. There are
two kinds:

| Token | Prefix | Scope | Use it for |
| ----- | ------ | ----- | ---------- |
| **Project API Key (PAK)** — recommended | `tgpak_` | a single project | CLI sync, CI, the MCP server |
| Personal Access Token (PAT) | `tgpat_` | every project you can access | when you need cross-project access (then pass `--project-id`) |

**Create a Project API Key:**

1. Create a free project at [app.tolgee.io](https://app.tolgee.io) (free tier =
   1,000 strings, unlimited seats). Note its **project ID** (in the URL/settings).
2. In the project → **Integrate** (or *Settings → API keys*) → **generate a
   Project API Key**. The Integrate wizard preselects the scopes the CLI needs
   (`keys` + `translations` read/write, `languages`); accept those.
3. Copy the `tgpak_…` value — it's shown **once**.

**Store it as an environment variable — never commit it.** `.mcp.json` and the
CLI both read `TOLGEE_API_KEY` from your shell:

```bash
export TOLGEE_API_KEY=tgpak_xxxxxxxxxxxxxxxxxxxx     # macOS/Linux
$env:TOLGEE_API_KEY = "tgpak_xxxxxxxxxxxxxxxxxxxx"   # PowerShell
```

For CI, add it as a repository secret. `frontend/.env.example` documents the var.

### One-time setup

1. Put your project ID in `frontend/.tolgeerc.json` → `"projectId": <id>`.
2. Export `TOLGEE_API_KEY` (above).
3. Seed Tolgee with the existing keys: `cd frontend && npm run i18n:push:seed`.

### Day-to-day (from `frontend/`)

```bash
npm run i18n:pull      # Tolgee → local JSON (after translating in the web UI)
npm run i18n:push      # local JSON → Tolgee (adds new keys, keeps existing)
npm run i18n:compare   # diff the keys used in code against the Tolgee project
```

### MCP server (AI-assisted translation)

`.mcp.json` registers the official **Tolgee MCP server** (HTTP,
`https://app.tolgee.io/mcp/developer`, authenticated with the `X-API-Key` header
from `${TOLGEE_API_KEY}`). With it, Claude Code / Claude Desktop can search keys,
add/update translations, and trigger machine translation without leaving the
editor. Your client will prompt to approve the server on first use.

### Self-hosting

To self-host Tolgee instead of using Cloud, change `apiUrl` in
`frontend/.tolgeerc.json` and the `url` in `.mcp.json` to your instance, and
supply your own machine-translation provider key.

> **Format note:** the locale files use Vue I18n `{name}`-style placeholders and
> `.tolgeerc.json` is set to `JSON_ICU`. Verify your first `push:seed` round-trip
> preserves `{name}` placeholders and `<b>` markup; switch `format` if your
> project needs a different mapping.

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
