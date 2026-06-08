# Changelog

All notable changes to this project are documented here.
Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project aims to follow [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added
- Test suites: backend PHPUnit (Translatable cast, locale-aware `/cms/site`,
  auth/login whitelist, protected routes, admin CRUD incl. per-locale writes,
  health) and frontend Vitest (i18n, axios interceptor, `useSiteContent`,
  `FrLangSwitcher`, config). Model factories for all domain models, a
  `SetsUpPassport` test trait, and a GitHub Actions CI workflow
  (`.github/workflows/ci.yml`). `/ship` now gates merges on a green suite.
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
- Bilingual CMS content (PL/EN): a tolerant `Translatable` JSON cast stores
  text per locale, a `SetLocale` middleware resolves `GET /cms/site?lang=`, and
  the frontend refetches content per locale. Inline admin edits update the
  currently-displayed language and preserve the other. Bilingual demo content
  seeded for budget, milestones, progress, expenses, FAQ, hero, and testimonial.

### Changed
- CORS now defaults to the configured `FRONTEND_URL` instead of `*`
  (override via `CORS_ALLOWED_ORIGINS`).
- `docker-compose.yml` reads `APP_KEY`, `FRONTEND_URL`, and `ADMIN_EMAILS` from
  the environment instead of hardcoded values.

### Fixed
- Admin route-model binding for the `faq` and `progress` API resources: the
  controller parameters (`$faqItem`, `$progressPost`) didn't match the route
  parameter names, so inline FAQ/progress edits and deletes silently hit an
  empty model. Route parameters are now bound explicitly. (Caught by new tests.)

### Security
- Removed the hardcoded Laravel `APP_KEY` and a personal admin email from
  `docker-compose.yml`; secrets now come from the gitignored `backend/.env`.
- Added a root `.gitignore` excluding build caches, local Claude settings, the
  orphaned root lockfile, and the private beneficiary research document from the
  public repository.
- Confirmed `.env`, the SQLite database, and Passport signing keys
  (`storage/*.key`) remain untracked.

### Planned
- Migrate remaining hardcoded UI strings across screens/components (and the
  desktop layouts) to i18n locale keys, and add the language switcher to desktop.
- Optional Tolgee sync (cloud free tier or self-hosted) layered over the local
  locale files, plus the Tolgee MCP server for AI-assisted translation.
