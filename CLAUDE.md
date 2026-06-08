# Adaś Lipiński — Project Instructions

## Tech Stack
- **Frontend**: Vue 3 + TypeScript, Vite, Tailwind CSS v4
- **State**: Pinia (auth store)
- **Data fetching**: TanStack Vue Query — use `useQuery` for reads, `useMutation` for writes
- **Icons**: lucide-vue-next
- **Backend**: Laravel 13, SQLite, Laravel Passport (JWT)

## UI Rules
- **Always use Tailwind** for layout, spacing, grid, flex, typography utilities
- **Always use TanStack Query** for any async data (API calls, loading states, mutations, cache)
- Theme colors use CSS oklch variables — apply via `:style` bindings, not Tailwind arbitrary values
- Mobile-first: all new pages start at mobile width, add `lg:` breakpoints for desktop
- Components live in `src/features/<feature>/components/` with `Fr` prefix (mobile) or `D` prefix (desktop)

## File Conventions
- Feature code: `src/features/<feature>/`
- Pages: `src/pages/`
- API client: `src/lib/api.ts` (axios with Bearer token)
- Auth store: `src/features/auth/store.ts`

## API
- Base URL: `http://localhost:8000/api` (dev), set via `VITE_API_URL`
- Auth: Bearer token in `Authorization` header (managed by axios interceptor)
- Endpoints: `/health`, `/auth/login`, `/auth/me`, `/auth/change-password`
- i18n: every request carries `?lang=<pl|en>` (axios interceptor); `/cms/site?lang=` returns localized content

## Testing
- **Backend** (PHPUnit, SQLite `:memory:`): `cd backend && php vendor/bin/phpunit`
  (NOTE: `php artisan test` is not registered — collision is in `dont-discover`).
  DB tests use `RefreshDatabase`; token tests use the `Tests\Concerns\SetsUpPassport`
  trait or `Passport::actingAs()`; domain models have factories.
- **Frontend** (Vitest + jsdom + Vue Test Utils): `cd frontend && npm run test`
  (and `npm run type-check`). Specs are co-located as `*.spec.ts`.
- **Convention**: a new feature or bugfix ships with its tests in the same change.
  Translatable content fields are stored as JSON `{pl,en}` via the `Translatable`
  cast — factory/seed values for those fields are arrays, not strings.
