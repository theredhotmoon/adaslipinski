# Changelog

All notable changes to this project are documented here.
Format based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/);
this project aims to follow [Semantic Versioning](https://semver.org/).

## [Unreleased]

### Added
- **Admin Site Settings** — a settings panel (gear in the admin bar) where admins choose
  the active **desktop layout** (Classic / Editorial / Dashboard) and **hide individual
  public sections** (story, budget, updates, expenses, tax, testimonials, foundation,
  partners, FAQ, gallery). The Astro site gained story/milestones, testimonials, and
  gallery sections so they can be shown or hidden too. Stored
  server-side via `GET|PUT /admin/settings` and exposed through a `settings` block on
  `/cms/site`. The layout drives the SPA desktop (the visitor switcher is now admin-only
  preview); section hiding applies to the public Astro site, while the SPA stays the full
  editing surface. Covered by feature tests.
- **Server-rendered public website** (`web/`) — a new Astro app that renders the
  public site to complete, SEO-indexable HTML while keeping the Laravel CMS and the
  Vue admin SPA untouched. It fetches `/cms/site?lang=` at build time and reuses the
  interactive UI as **Vue islands** (donate modal/triggers, FAQ accordion) that
  coordinate via a nanostore; everything else ships as zero-JS static HTML. Real
  per-locale URLs (`/pl`, `/en`) with `hreflang` + canonical (replacing the SPA's
  client-only locale switch), one page per progress post, `NGO`/`Article` JSON-LD,
  auto `sitemap.xml`, and responsive image optimization for CMS media via
  `astro:assets`. Builds fall back to bundled content when the API is offline.
- **Static-site rebuild hook** (backend) — when published CMS content changes, a
  debounced, queued job (`TriggerSiteRebuild` + `CmsContentObserver`) pings a deploy
  hook (`DEPLOY_HOOK_URL`) to rebuild & redeploy the public site. Disabled by default
  (no-op until the URL is set); a burst of edits coalesces into one rebuild. Covered
  by feature tests.
- CI coverage for the public site: a **`web` job** runs `astro check` + a full
  `astro build` (exercising the bundled-fallback path, since the API isn't reachable
  in CI), and a **`deploy-web` job** pings the host build hook on pushes to `main`
  after the suite is green. The deploy job reads the `WEB_DEPLOY_HOOK_URL` Actions
  secret and skips cleanly until it's set, so a *code* change to `web/` redeploys the
  site (the backend `DEPLOY_HOOK_URL` covers *content* changes).
- Image upload for the **About gallery** and **testimonial photos** — completing
  image upload across every slot. New `gallery_images` table + `GalleryImage`
  model + admin CRUD (`/admin/gallery`); the About "everyday life" grid renders
  uploaded photos with add/delete. The About quote is now driven by the CMS
  testimonial (falls back to i18n) with its photo uploadable. `GET /cms/site`
  returns a `gallery` array and `testimonials[].id`.
- Image upload for the **hero image** and **partner logos** (in addition to
  news/progress): admins upload via the same `AdminImageUpload` control, and the
  images render on mobile + desktop. Hero added a `hero_image_id` column on
  beneficiaries; partner `logo_id` was already supported. `GET /cms/site` now
  returns `child.heroImageUrl` and `partners[].logoUrl`.
- Image upload for news/progress posts: admins can upload a photo to a post
  (file → `POST /admin/media` → attached via `image_id`), and posts render the
  real image (mobile + desktop) instead of a placeholder. New `AdminImageUpload`
  component + `useUploadMedia`; `DPh` gained image rendering; `storage:link`
  wired into the Docker entrypoint so uploads are web-served. Feature tests
  cover upload + mime rejection + attach-to-site + auth.
- Tolgee translation sync (optional): `@tolgee/cli` + `frontend/.tolgeerc.json`
  (Tolgee Cloud, JSON_ICU), `npm run i18n:push/pull/compare` scripts, the
  official Tolgee MCP server in `.mcp.json`, and a README "Translations" guide.
  Locale files remain the source of truth; the API key lives in shell env.
- Desktop language switcher (`DLangSwitcher`) in the top nav — PL/EN parity with
  the mobile shell across all three desktop layouts.
- Internationalization: every UI string (mobile + desktop chrome) migrated to
  Vue I18n `t()` keys, on top of the already-bilingual CMS content.
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
- CI: the `web` job runs on Node 22 (Astro 6 requires `>=22.12`), and
  `actions/checkout` + `actions/setup-node` bumped to v5 (Node 24 action runtime)
  to clear the Node 20 deprecation warning.
- CORS now defaults to the configured `FRONTEND_URL` instead of `*`
  (override via `CORS_ALLOWED_ORIGINS`).
- `docker-compose.yml` reads `APP_KEY`, `FRONTEND_URL`, and `ADMIN_EMAILS` from
  the environment instead of hardcoded values.

### Fixed
- Admin login returned **500 on a fresh stack** because no Passport personal access
  client existed (`createToken` requires one). The Docker entrypoint now creates it
  (guarded against duplicates) and the README quickstart documents the local step.
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
