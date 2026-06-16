# Admin Panel — Feature Reference

The admin panel is the `frontend/` Vue 3 SPA. Since the public site moved to the
Astro app (`web/`), this SPA's **only** job is content maintenance: an authenticated
editor renders the fundraising page as a live, WYSIWYG-editable preview and writes
changes back to the Laravel CMS API (`/api/admin/*`). Saving content triggers a
rebuild of the static public site.

> This doc is the canonical list of admin capabilities — keep it in sync when admin
> features change. It also drives the e2e test plan (`docs/admin-panel-e2e-plan.md`).

## Access & authentication

| Behavior | Where | Notes |
| --- | --- | --- |
| Whole app is auth-gated | `src/router/index.ts` | `/` (editor) has `meta.requiresAuth`; anonymous → `/login` |
| Login | `/login` → `LoginPage.vue` → `auth.login()` | Bearer token stored in `localStorage`, sent by axios interceptor |
| Guest redirect | router guard | authenticated user hitting `/login` → `/` |
| Change password | `/change-password` (auth-only) | `POST /auth/change-password` |
| Logout | toolbar (`AdminFloatingBar`) | clears token → redirect `/login` |
| Edit-mode toolbar | `AdminFloatingBar.vue` | green "Admin mode" badge, settings gear, logout, signed-in email |

Seeded admin (dev + CI): `admin@example.com` / `password123`.

## Editing model

Editing is **inline / WYSIWYG** — the admin edits the real page in place, not a
separate form-only dashboard.

| Mechanism | Component | Behavior |
| --- | --- | --- |
| Inline text | `InlineText.vue` | `contenteditable`; saves on blur if changed; `Esc` reverts; `Enter` commits (single-line); paste is plain-text |
| Inline number | `InlineNumber.vue` | numeric inline edit |
| Create | `AdminAdd.vue` | "add" affordance for collections |
| Delete | `AdminDelete.vue` | delete affordance with confirm (`admin.confirmDelete`) |
| Modal form | `AdminFormModal.vue` | create/edit forms + Site Settings shell (title, Save, saving state) |
| Image upload | `AdminImageUpload.vue` | `POST /admin/media` (multipart) → `{ id, url }` |

Editable fields only show their editable affordance while authenticated
(`auth.isAuthenticated`), so the same components render read-only for the preview.

## Editable content domains

All mutations go through `src/features/admin/useCmsApi.ts`. Each write invalidates the
`cms-site` query so the live preview reflects the change immediately.

| Domain | Operations | Endpoints |
| --- | --- | --- |
| Beneficiary (child) | update | `PATCH /admin/beneficiary` |
| Foundation | update | `PATCH /admin/foundation` |
| Progress posts | create / update / delete | `POST,PUT,DELETE /admin/progress[/{id}]` |
| Budget items | update | `PATCH /admin/budget-items/{id}` |
| Milestones | create / update / delete | `POST,PUT,DELETE /admin/milestones[/{id}]` |
| Expenses | create / update / delete | `POST,PUT,DELETE /admin/expenses[/{id}]` |
| FAQ | create / update / delete | `POST,PUT,DELETE /admin/faq[/{id}]` |
| Partners | create / update / delete | `POST,PUT,DELETE /admin/partners[/{id}]` |
| Testimonials | update | `PUT /admin/testimonials/{id}` |
| Gallery | create / delete | `POST,DELETE /admin/gallery[/{id}]` |
| Media | upload | `POST /admin/media` |

## Site settings

`AdminSiteSettings.vue` (opened from the toolbar gear) → `GET/PUT /admin/settings`:

- **Active desktop layout** — one of `classic | editorial | dashboard` (`LayoutId`).
  Admin-controlled; public visitors no longer switch it themselves.
- **Public section visibility** — checkboxes per section; hidden sections are omitted
  from the Astro public build (the SPA preview still renders everything). Changes are
  staged locally and committed on **Save**.

## Internationalization

- Content fields are translatable `{ pl, en }` (Laravel `Translatable` cast); the admin
  edits the locale currently selected via `FrLangSwitcher`.
- Every API request carries `?lang=<pl|en>` (axios interceptor).

## Data & rebuild pipeline

- Reads via TanStack `useQuery` (`cms-site`, `admin-settings`); writes via `useMutation`
  with cache invalidation.
- On the backend, CMS model changes fire `CmsContentObserver` → `TriggerSiteRebuild`
  (debounced, queued, gated on `DEPLOY_HOOK_URL`) so content edits rebuild the static
  public site.

## Test coverage status

- **Unit (Vitest):** auth-guard routing, i18n, lang switcher, API client, site config.
- **E2e (Playwright, `frontend/e2e/`):** auth gate, inline-edit persistence, site
  settings (layout + section visibility), and FAQ CRUD. Runs in CI (`frontend-e2e`
  job, against a seeded backend) and locally — see `frontend/e2e/README.md`. Plan in
  `docs/admin-panel-e2e-plan.md`.
