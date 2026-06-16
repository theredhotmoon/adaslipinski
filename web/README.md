# web/ — public site (Astro + Vue islands)

Server-rendered, SEO-first public website for Adaś Lipiński. It renders complete HTML
and reuses the project's interactive UI as **Vue islands**. The Laravel backend
(`../backend`) stays a headless CMS; the Vue admin SPA (`../frontend`) is untouched.

```
../backend  → Laravel: /cms/site?lang= API + /admin SPA + media   (unchanged)
web/        → Astro: builds /pl & /en HTML from that API          (this app)
../frontend → Vue admin SPA                                        (unchanged)
```

## Stack
- **Astro 6** (static output / SSG) — full HTML at build time, near-zero JS baseline.
- **@astrojs/vue** — existing-style Vue components run as hydrated islands.
- **Tailwind v4** via `@tailwindcss/postcss` (see note below).
- **nanostores** — shared state across isolated islands (the donate modal).
- **@astrojs/sitemap** — `sitemap-index.xml` at build.

## Commands
```bash
npm install
cp .env.example .env      # point CMS_API_URL at the running Laravel API
npm run dev               # local dev server
npm run build             # static build → dist/
npm run preview           # serve dist/ locally
npm run check             # astro check (types for .astro + .vue)
```

If the API is unreachable at build time, the build still succeeds using the bundled
fallback content in `src/lib/fallback.ts` (a warning is logged per locale).

## Structure
```
src/
  i18n/
    config.ts          locales (pl, en), money formatting (zl)
    ui.ts              useT(locale) — server-side string lookup, no vue-i18n in islands
    locales/*.json     UI chrome strings (copied from ../frontend; content is in the CMS)
  lib/
    types.ts           SiteContent — mirror of the SPA's type (keep in sync by hand)
    cms.ts             fetchSite(lang) — build-time fetch with fallback
    fallback.ts        offline/bundled content
    donateStore.ts     nanostore bridging donate triggers ↔ the single modal
  components/
    BaseHead.astro     title/meta/canonical/hreflang/OG + JSON-LD
    sections/*.astro   static content sections (Hero, Budget, Progress, …)
    islands/*.vue      interactive bits (DonateTrigger, DonateModal, FaqAccordion)
  layouts/BaseLayout.astro
  pages/
    index.astro        → redirects to /pl
    [lang]/index.astro            /pl, /en   (getStaticPaths over locales)
    [lang]/progress/[id].astro    one page per post per locale, Article JSON-LD
```

## i18n
Real per-locale URLs (`/pl`, `/en`) with `hreflang` + `x-default` and a canonical —
replacing the SPA's client-only locale switch. Content comes localized from
`/cms/site?lang=`; UI chrome comes from `src/i18n/locales/*.json`.

## Island rules (why components here look decoupled)
Islands are isolated — no shared Vue app, so Pinia / vue-router / vue-i18n / TanStack
do **not** cross island boundaries. So islands here take **plain serializable props**
and never fetch. Triggers and the modal coordinate through the `donateStore` nanostore.

## Tailwind note
We use `@tailwindcss/postcss` (not `@tailwindcss/vite`). Astro 6 bundles rolldown-vite,
whose resolver the Vite plugin variant isn't yet compatible with (it throws
`Missing field 'tsconfigPaths'`). PostCSS produces the same result without that coupling.

## Deploy & rebuild

> **No server/host yet?** That's fine — nothing here is required to develop or build.
> Leave `DEPLOY_HOOK_URL` **unset** and the whole rebuild integration stays dormant
> (the observer/job are complete no-ops). You can build and preview locally with
> `npm run build` + `npm run preview` today. When you pick a host later, do three
> things: (1) deploy `dist/`, (2) create a build hook on that host, (3) set
> `DEPLOY_HOOK_URL` to it in the backend `.env` and run `php artisan queue:work`.
> Until then, ship content by running a build manually.

Built as static `dist/` → deploy to any static host/CDN. Because content is baked at
build time, a **rebuild trigger** is wired on the Laravel side: changes to any CMS
content model ping a deploy hook to rebuild & redeploy this site.

- Backend: `App\Jobs\TriggerSiteRebuild` + `App\Observers\CmsContentObserver`
  (registered in `AppServiceProvider`). Set `DEPLOY_HOOK_URL` in the backend `.env` to a
  Netlify/Vercel build hook or CI trigger. Empty = disabled. A burst of edits is
  debounced into one rebuild (`DEPLOY_HOOK_DEBOUNCE`, default 60s). Needs a queue worker
  (`php artisan queue:work`).

For any route that must be always-fresh instead, add an SSR adapter
(`@astrojs/node`/`vercel`/`cloudflare`) and put `export const prerender = false` in it.

## Remote images
CMS media renders through `src/components/CmsImage.astro`, which wraps Astro's `<Image>`
(`astro:assets`) for responsive, optimized output (webp/avif via sharp). Allowed source
hosts are set in `image.remotePatterns` (`astro.config.mjs`) — tighten the bare `https`
rule to your real storage host for production. Optimization runs at build time, so the
media host must be reachable during a build/rebuild.
