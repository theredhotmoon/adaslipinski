# Changelog

All notable changes to this project are documented here.
Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project aims to follow [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added
- Open-source project scaffolding: root `README.md` (overview, quickstart,
  config table, security notes), `LICENSE` (MIT), and `CHANGELOG.md`.
- Environment templates documenting app-specific config: `backend/.env.example`
  (`ADMIN_EMAILS`, `FRONTEND_URL`, `CORS_ALLOWED_ORIGINS`) and a new
  `frontend/.env.example` (`VITE_API_URL`).
- Claude Code skills under `.claude/commands/`: `/pm` (project status),
  `/deep-research`, `/ship`, and language reviews `/review-php`, `/review-ts`,
  `/review-js` (Vue covered by existing `/vue-review`).
- Laravel 13 CMS backend: Passport/JWT auth with email-whitelist login, public
  `GET /cms/site`, and admin CRUD for budget, expenses, progress, milestones,
  FAQ, partners, donations, testimonials, foundation, media, and config.
- Vue 3 fundraising site: mobile + desktop component sets, three desktop
  layouts, and inline CMS editing gated on auth.
- Internationalization foundation (Vue I18n): Polish (default) + English locale
  files, browser/localStorage locale detection with `<html lang>` sync, and a
  language switcher in the mobile shell.
- Central rebrandable site config (`frontend/src/config/site.ts`): one source of
  truth for identity, foundation, bank details, contact, donations, currency,
  and available locales.

### Changed
- CORS now defaults to the configured `FRONTEND_URL` instead of `*`
  (override via `CORS_ALLOWED_ORIGINS`).
- `docker-compose.yml` reads `APP_KEY`, `FRONTEND_URL`, and `ADMIN_EMAILS` from
  the environment instead of hardcoded values.

### Security
- Removed the hardcoded Laravel `APP_KEY` and a personal admin email from
  `docker-compose.yml`; secrets now come from the gitignored `backend/.env`.
- Added a root `.gitignore` excluding build caches, local Claude settings, the
  orphaned root lockfile, and the private beneficiary research document from the
  public repository.
- Confirmed `.env`, the SQLite database, and Passport signing keys
  (`storage/*.key`) remain untracked.

### Planned
- Migrate remaining UI strings across screens/components to i18n locale keys.
- Full bilingual CMS *content* via a per-locale backend schema and a
  locale-aware `GET /cms/site?lang=` endpoint.
- Optional Tolgee sync (cloud free tier or self-hosted) layered over the local
  locale files, plus the Tolgee MCP server for AI-assisted translation.
