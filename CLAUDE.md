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
