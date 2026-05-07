# Laravel 13 Skeleton

Production-grade base scaffold for **Laravel 13 + Inertia 2 + Vue 3 + TS + Tailwind 4 + DaisyUI 5** applications. Auth, RBAC (Spatie Permission), audit log (Spatie Activitylog), i18n (sk/en), Users + Roles management, app:demo command — all wired up.

Production-grade base scaffold built with opinionated conventions.

---

## Stack

| Layer | Tooling |
|---|---|
| Runtime | PHP **8.5** |
| Framework | Laravel **13.4** |
| Frontend | Inertia **2** + Vue **3.5** + TypeScript **6** |
| Build | Vite **7** + `laravel-vite-plugin@^1` + `@vitejs/plugin-vue@^6` |
| Styling | Tailwind CSS **4** + DaisyUI **5** (single OKLCH theme `app-theme`) |
| Database | SQLite (dev) / PostgreSQL **16** (prod via Docker) |
| Testing | PHPUnit **12** + Inertia testing helpers |
| Quality | Pint, Larastan (level 5), ESLint flat config, Prettier, vue-tsc, Lefthook |
| CI | Bitbucket Pipelines |

### Composer packages

`spatie/laravel-data`, `spatie/laravel-permission`, `spatie/laravel-activitylog` v5, `spatie/laravel-medialibrary`, `spatie/laravel-typescript-transformer` v3, `spatie/laravel-query-builder`, `inertiajs/inertia-laravel`, `tightenco/ziggy`, `knuckleswtf/scribe`.

Dev: `laravel/pint`, `larastan/larastan`, `laravel/boost`, `laravel/pail`.

### Pnpm packages

Runtime: `@inertiajs/vue3`, `vue`, `ziggy-js`.

Dev: `vite@^7`, `laravel-vite-plugin@^1`, `@vitejs/plugin-vue`, `tailwindcss@^4`, `@tailwindcss/vite`, `daisyui@^5`, `@heroicons/vue`, `typescript@^6`, `vue-tsc`, `eslint@^10` + plugins, `vue-eslint-parser`, `prettier`, `lefthook`.

---

## Quick start

### Prerequisites

- PHP **8.5+** (`brew install php@8.5`)
- Composer 2.8+
- Node 22+ + `pnpm` (always pnpm, never npm/yarn)
- Docker (optional — only for the bundled Postgres + Redis stack)

### Local setup (5 commands)

```bash
composer install
pnpm install
cp .env.example .env
php artisan key:generate
php artisan app:demo --fresh
```

Then in two terminals:

```bash
pnpm dev          # vite hot reload on http://localhost:5173
php artisan serve # app on http://localhost:8000
```

Open <http://localhost:8000> → redirects to `/login`.

### Default credentials (hard contract)

```
Email:    admin@example.com
Password: password
```

This account **must** work after every `php artisan migrate:fresh --seed` and every `php artisan app:demo --fresh`. CI smoke test asserts it.

---

## Daily commands

| Action | Command |
|---|---|
| Reset & seed demo DB | `php artisan app:demo --fresh` |
| Run all tests | `php artisan test` |
| Pint format (auto-fix) | `vendor/bin/pint` |
| Pint check (CI mode) | `vendor/bin/pint --test` |
| PHPStan | `vendor/bin/phpstan analyse --memory-limit=2G` |
| Generate TS types from DTOs/Enums | `php artisan typescript:transform` |
| Vite dev server | `pnpm dev` |
| Production build | `pnpm build` |
| TS typecheck | `pnpm typecheck` |
| ESLint check | `pnpm lint:js` |
| ESLint auto-fix | `pnpm fix:js` |
| Prettier check | `pnpm lint:prettier` |
| Prettier auto-fix | `pnpm fix:prettier` |
| Full lint | `pnpm lint` |
| Full auto-fix | `pnpm fix` |

---

## Docker (optional)

Bundled compose stack: PHP 8.5-CLI + Postgres 16 + Redis 7 + Vite dev server.

```bash
docker compose up -d
docker compose exec app php artisan app:demo --fresh
```

`.env` defaults: `DB_HOST=db`, `DB_DATABASE=app`, `DB_USERNAME=app`, `DB_PASSWORD=secret`, `REDIS_HOST=redis`.

---

## Architecture

```
HTTP request
  → web middleware (LocaleMiddleware → HandleInertiaRequests)
  → Controller (thin; authorizes via $user->can(...))
  → DTO (Spatie Data; validates via PHP attributes)
  → Service (final readonly; transactional; registered as singleton)
  → Eloquent Model (LogsActivity; casts() method)
  → DTO::fromModel(...) for response
  → Inertia::render('PageName', [...])
```

### Project layout

```
app/
├── Concerns/HasUuids.php            UUIDv7 trait
├── Console/Commands/AppDemoCommand  artisan app:demo --fresh
├── Data/                            Spatie Data DTOs (#[TypeScript])
│   ├── Auth/                        LoginData, NewPasswordData, ...
│   ├── Profile/
│   ├── Users/
│   ├── Roles/
│   ├── AuditLogs/
│   ├── Language/
│   └── Support/AuthenticatedUserReference
├── Enums/SupportedLanguage          Backed enum, #[TypeScript]
├── Http/
│   ├── Controllers/                 Thin controllers
│   └── Middleware/
│       ├── LocaleMiddleware
│       └── HandleInertiaRequests    .share() map
├── Listeners/LogAuthenticationActivity   Login/Logout/Failed → activity()
├── Models/
│   ├── User                         HasRoles + LogsActivity + CanResetPassword
│   └── Role                         extends Spatie Role + LogsActivity + scope search
├── Policies/                        User/Role/Activity
├── Providers/
│   ├── AppServiceProvider           Registers services + policies + Gate::before(admin)
│   └── TypeScriptTransformerServiceProvider
└── Services/                        final readonly, registered as singletons
    ├── UserService
    ├── RoleService                  System role invariants (admin can't be renamed/deleted)
    └── ProfileService

resources/
├── css/app.css                      Tailwind 4 import + DaisyUI plugin + OKLCH theme
├── js/
│   ├── app.ts                       Inertia bootstrap + ZiggyVue
│   ├── Components/
│   │   ├── ConfirmDeleteModal.vue
│   │   ├── Pagination.vue
│   │   └── PermissionManager.vue    Checkbox grid grouped by resource
│   ├── Composables/
│   │   ├── useFilters.ts            Debounced search + sort + perPage helpers
│   │   ├── useDeleteConfirm.ts
│   │   ├── useToast.ts
│   │   └── useTranslate.ts
│   ├── Layouts/
│   │   ├── AppLayout.vue            Drawer shell + sidebar nav + lang switcher + toasts
│   │   ├── Header.vue               Title + breadcrumbs + actions slot
│   │   └── DataTable.vue            generic <TRow extends object> table
│   ├── Pages/
│   │   ├── Auth/{Login,ForgotPassword,ResetPassword}.vue
│   │   ├── Profile/Show.vue
│   │   ├── Dashboard.vue
│   │   ├── Users/{Index,Create,Edit,Show}.vue
│   │   ├── Roles/{Index,Create,Edit}.vue
│   │   └── AuditLogs/{Index,Show}.vue
│   └── types/
│       ├── generated.d.ts           Auto-generated from DTOs/Enums
│       └── index.d.ts               Shared FE types
├── lang/{sk,en}/app.php             Flattened via Arr::dot in HandleInertiaRequests
└── views/app.blade.php              @vite + @inertia + @routes (Ziggy)

routes/web.php                       Guest group (auth flows) + auth group (everything else)
config/permission.php                role => App\Models\Role::class

database/
├── migrations/                      users (+locale +is_active) + cache + jobs + spatie tables
├── factories/UserFactory.php
└── seeders/
    ├── DatabaseSeeder
    ├── PermissionSeeder             11 permissions, admin/user roles, admin gets all
    └── UserSeeder                   admin@example.com (firstOrCreate) + 5 demo users

tests/
├── Feature/
│   ├── Auth/AdminLoginTest          admin/password authenticates → /dashboard
│   ├── AppDemoCommandTest           php artisan app:demo creates admin
│   └── ExampleTest                  /up health endpoint + / redirect
└── TestCase.php
```

---

## Conventions

### Backend (PHP / Laravel)

- **Strict types** — every PHP file: `declare(strict_types=1);` (enforced by Pint)
- **Final classes** — non-abstract classes are `final` (Pint `final_class`)
- **Single quotes** — Pint
- **DTO pipeline** — Controller → DTO (Spatie Data + attribute validation) → Service (`final readonly`) → Model. **No Form Requests, no JsonResource, no Repository pattern.**
- **Validation** — Spatie Data attributes (`#[Required]`, `#[Email]`, `#[Unique]`, `#[Confirmed]`, `#[Max]`, …) + contextual `rules()` for `Rule::unique()->ignore($id)` on update flows
- **Authorization** — Policies wired in `AppServiceProvider::boot()`, `Gate::before` super-admin shortcut for `admin` role; in controllers `$this->authorize($ability, $model)`
- **Filtering / sorting** — Spatie QueryBuilder: `allowedFilters([AllowedFilter::scope|exact|callback])` + `allowedSorts([])` + `defaultSort(...)` + `paginate($filters->perPage)->withQueryString()->through(fn($m) => XxxListItemData::fromModel($m))`
- **Search scope** — PG-aware operator: `config('database.default') === 'pgsql' ? 'ilike' : 'like'`
- **Audit log** — domain models use `Spatie\Activitylog\Models\Concerns\LogsActivity` + `getActivitylogOptions()` (`logOnly + logOnlyDirty + dontLogEmptyChanges`)
- **i18n** — server-side `__('app.key')`, all keys in `resources/lang/{sk,en}/app.php`
- **Inertia shared props** — exclusively in `HandleInertiaRequests::share()`; never attach ad-hoc props in controllers
- **Routes** — `routes/web.php` only (no API yet); guest group for auth flows, auth group for everything else

### Frontend (Vue / TS / Inertia)

- **Layouts** — every page wraps in `<AppLayout>` and starts with `<Header>`
- **Forms** — always `useForm({...})` from `@inertiajs/vue3`; submit buttons show `loading loading-spinner loading-xs` while `form.processing`; per-field errors via `:class="{ 'input-error': form.errors.x }"` + `<p class="text-error">`
- **Translations in templates** — `useTranslate()` composable: `const t = useTranslate(); ... t('users')`
- **Form scaffolding** — `fieldset.fieldset > legend.fieldset-legend > input.input.w-full`
- **Generic table** — reuse `DataTable<TRow>` with `:columns`, `:rows`, `cell-<key>` slots; click-to-sort header; perPage selector
- **`ref` is BANNED** for application logic (ESLint `no-restricted-syntax`). Use props/emits, Inertia shared props, Pinia, or composables. Template refs are allowed for unavoidable imperative DOM access.
- **TS types** — generated by `php artisan typescript:transform` (Lefthook hook auto-runs on DTO/Enum changes); reference as `App.Data.UserListItemData`, `App.Enums.SupportedLanguage`
- **`route()` helper** — Ziggy via `ZiggyVue` plugin; available globally in templates and `<script setup>`

---

## Hard contracts (DO NOT CHANGE)

1. **`admin@example.com` / `password`** — canonical demo admin must always work after `migrate:fresh --seed` and `app:demo --fresh`. CI smoke test asserts it.
2. **`App\Models\Role`** extends `Spatie\Permission\Models\Role` (wired via `config/permission.php`). Adds `LogsActivity` and `search` scope.
3. **`RoleService::SYSTEM_ROLES = ['admin']`** — system roles cannot be renamed or deleted.
4. **`HandleInertiaRequests::share()` map** — `translations`, `flash`, `auth`, `can`, `locale`, `languages`, `canResetPassword`. Extend the share map (and `App.SharedProps`) when adding new global state. Never attach ad-hoc props in controllers.

---

## Pre-commit hooks (Lefthook)

`pnpm exec lefthook install` (already installed by `/bootstrap`).

| Hook | When | What |
|---|---|---|
| `pre-commit` | Every commit | Pint (auto-fix), PHPStan, ESLint (auto-fix), Prettier (auto-fix), `php artisan typescript:transform` (auto-stage `generated.d.ts`) |
| `pre-push` | Every push | `vue-tsc --noEmit`, `php artisan test --parallel` |

---

## CI (Bitbucket Pipelines)

`bitbucket-pipelines.yml` runs the same gates as Lefthook plus:

1. `composer install`
2. `pnpm install --frozen-lockfile`
3. `php artisan typescript:transform` + `git diff --exit-code resources/js/types/generated.d.ts` — fails the build if a DTO/Enum change wasn't committed with the regenerated types
4. `vendor/bin/pint --test`
5. `vendor/bin/phpstan analyse`
6. `pnpm exec eslint resources/js/ vite.config.js`
7. `pnpm exec prettier --check ...`
8. `pnpm exec vue-tsc --noEmit`
9. `php artisan test`
10. `pnpm build`

Image: `php:8.5-cli`. Caches: composer, pnpm, node.

---

## Tooling discoveries / gotchas

Quirks discovered during bootstrap:

- **Laravel 13 Eloquent attributes** — `Fillable`, `Hidden`, `Visible`, `Appends`, `Table`, `Connection`, `DateFormat`, `ObservedBy`, `UseFactory`, `UsePolicy`, `Scope`, `ScopedBy` exist as PHP attributes. **`#[Cast]` does NOT** — keep using the `casts()` method.
- **`spatie/laravel-data` × `spatie/typescript-transformer@^3` incompatibility** — `DataTypeScriptTransformer` references a removed `DtoTransformer` class. Workaround: use `AttributedClassTransformer` + `#[TypeScript]` on every DTO.
- **`spatie/laravel-activitylog` v5** — namespace moved: `Spatie\Activitylog\Models\Concerns\LogsActivity` + `Spatie\Activitylog\Support\LogOptions`. Method renamed: `dontSubmitEmptyLogs()` → `dontLogEmptyChanges()`.
- **Vite v8 + plugin-vue v6** has parse errors on `<script setup lang="ts">` in current rolldown. Pinned to `vite@^7` + `laravel-vite-plugin@^1`.
- **`bootstrap/app.php->withEvents()`** accepts `iterable|bool`, not `Closure`. Register listeners in `AppServiceProvider::boot()` via `Event::listen(...)`.
- **`typescript-transformer` SP `outputDirectory`** defaults to `js/generated`. Override in custom Service Provider to `js/types` (where `generated.d.ts` ends up alongside `index.d.ts`).
- **DaisyUI 5** — theme defined via CSS `@plugin 'daisyui/theme'` block, not a config file.

---

## Adding a business entity (Resource Recipe)

For each new domain entity `Xxx`, the recipe creates, in order:

1. Migration → Model (with `LogsActivity` + scopes) → Factory → Seeder
2. Enum(s) for finite states
3. Policy + permission strings in `PermissionSeeder`
4. DTOs: `XxxIndexFilterData`, `XxxListItemData::fromModel`, `XxxStoreData`, `XxxUpdateData`, `XxxDetailData`
5. Service (singleton in `AppServiceProvider`)
6. Controller using QueryBuilder pattern
7. Routes (`Route::resource(...)` inside auth group)
8. Inertia pages: `Index/Create/Edit/Show.vue` reusing `AppLayout`, `Header`, `DataTable`, `useFilters`, `useDeleteConfirm`, `ConfirmDeleteModal`
9. Translation keys in `resources/lang/{sk,en}/app.php`
10. Sidebar nav entry in `AppLayout.vue` + `can` map in `HandleInertiaRequests`
11. `php artisan typescript:transform` + `pnpm fix:js`
12. Feature + unit tests

---

## Anti-patterns to refuse

- ❌ Form Requests (use Spatie Data DTOs)
- ❌ `JsonResource` (return DTOs directly from controllers)
- ❌ Repository pattern (services hit Eloquent directly)
- ❌ Business logic in controllers or models (always in services)
- ❌ `npm` / `yarn` (always `pnpm`)
- ❌ Skipping `php artisan typescript:transform` after DTO/Enum changes
- ❌ Bypassing `HandleInertiaRequests::share()` with ad-hoc props
- ❌ Hard-coded user-visible strings (always `__('app.…')` / `t('…')`)
- ❌ Vue `ref` for inter-component state or application logic (template refs only)
- ❌ Changing `admin@example.com` / `password` demo invariant

---

## License

MIT.
