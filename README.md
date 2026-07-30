# MyPortfolio

A Laravel SaaS portfolio platform with **API-first** Sanctum authentication and a custom Blade + Tailwind UI.

## Tech Stack

- **PHP** 8.4+
- **Laravel** 13.x
- **Laravel Sanctum** (API token auth)
- **Blade + Tailwind CSS + Vite** (custom UI)
- **MySQL** (Laragon recommended)

## Features

- API authentication: register, login (email **or** username), logout, me/user
- User-owned portfolio URL: `/{username}` (e.g. `myportfolio.com/jane`)
- Public portfolio API: `GET /api/portfolio/{username}` (active data only)
- Admin panel under `/admin/*` (Sanctum token in `localStorage`)
- Strict multi-tenant isolation via `user_id` on every module
- Roles: `admin`, `user` (platform admin API only — each user is independent)
- Consistent JSON response format
- Default admin seeder

## Requirements

- PHP >= 8.4.1
- Composer
- Node.js & npm
- MySQL (Laragon recommended)

## Installation

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Configure database in `.env` (Laragon example):

```env
APP_URL=http://myportfolio.test
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=myportfolio
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
```

## Local Development (Laragon)

1. Start **Laragon** (Apache/Nginx + MySQL) — this serves Laravel
2. Run Vite for assets:

```bash
npm run dev
```

Open your virtual host (e.g. `http://myportfolio.test`).

> Do **not** use `php artisan serve` when Laragon is already hosting the site.

## Default Admin

| Field    | Value               |
|----------|---------------------|
| Email    | `admin@example.com` |
| Username | `siteadmin`         |
| Password | `password`          |
| Role     | `admin`             |

```bash
php artisan db:seed --class=AdminSeeder
```

## Frontend Pages

| URL                  | Description                                      |
|----------------------|--------------------------------------------------|
| `/`                  | Redirects to `/login`                            |
| `/login`             | Login (email or username) → `/admin`             |
| `/register`          | Registration (claims username URL) → `/admin`    |
| `/admin`             | Dashboard + “View Portfolio”                     |
| `/admin/profile`     | Profile editor (API-backed)                      |
| `/admin/sections`    | Sections (placeholder)                           |
| `/admin/skills`      | Skills module                                    |
| `/admin/projects`    | Portfolio items (projects / case studies / works) |
| `/admin/experience`  | Experience (timeline)                            |
| `/admin/education`   | Education (timeline)                             |
| `/admin/theme`       | Theme — frontend/public portfolio (coming soon)  |
| `/admin/settings`    | Settings — admin brand colours                   |
| `/{username}`        | Public portfolio page                            |

### Admin sidebar

- Dark fixed sidebar on desktop (`bg-gray-900`), slide-over on mobile
- Menu config: `config/admin_navigation.php`
- Filter helper: `App\Support\AdminNavigation` (role + modules ready)
- JS mirror for future SPA: `resources/js/config/sidebarMenu.js`
- Component: `resources/views/components/admin/sidebar.blade.php`
- Layout: `resources/views/layouts/dashboard.blade.php`

### Admin brand colours (Settings only)

- **Not** part of Theme — Theme is reserved for the public frontend later
- Fields: `admin_primary`, `admin_secondary` (`#rrggbb`)
- Defaults: `#0d9488` / `#14b8a6`
- **Primary** → accents (active nav, buttons, logo chip)
- **Secondary** → sidebar / navbar background
- Applied only inside the admin shell via CSS variables (`--color-primary`, etc.)
- UI: `/admin/settings` — colour picker + hex inputs + live preview
- Persist: `settings` table; `GET /api/settings`, `PUT /api/admin/settings` (admin role)

Auth flow (client-side):

1. Register/Login stores Sanctum token in `localStorage` (`auth_token`)
2. Redirect to `/admin` after success
3. Admin pages load user via `Authorization: Bearer {token}`
4. Missing/invalid token redirects to `/login`
5. Logout calls `POST /api/logout`, clears token, redirects to `/login`
6. Public portfolio: `/{username}` → `GET /api/portfolio/{username}` (no auth)

JS modules:

```
resources/js/
├── api.js              # fetch wrapper + token helpers
├── auth.js             # login / register / logout / me
├── theme.js            # applyAdminTheme (admin panel only)
├── app.js              # page bootstrap
└── pages/
    ├── login.js
    ├── register.js
    ├── dashboard.js
    ├── portfolio.js    # public /{username}
    └── settings.js
```

## API Response Format

```json
{
  "status": true,
  "message": "string",
  "data": {}
}
```

## Auth Endpoints

| Method | URI             | Auth | Description                          |
|--------|-----------------|------|--------------------------------------|
| POST   | `/api/register` | No   | Register (name, username, email, password) |
| POST   | `/api/login`    | No   | Login with `login` (email **or** username) + password |
| POST   | `/api/logout`   | Yes  | Revoke current token                 |
| GET    | `/api/me`       | Yes  | Current user                         |
| GET    | `/api/user`     | Yes  | Alias of `/api/me`                   |
| GET    | `/api/portfolio/{username}` | No | Public portfolio (active data only) |
| GET    | `/api/settings` | Yes  | Admin brand colours                  |

### Register (example)

```http
POST /api/register
Accept: application/json
Content-Type: application/json

{
  "name": "Jane Doe",
  "username": "jane",
  "email": "jane@example.com",
  "password": "password",
  "password_confirmation": "password"
}
```

### Login (example)

```http
POST /api/login
Accept: application/json
Content-Type: application/json

{
  "login": "jane",
  "password": "password"
}
```

Username rules: lowercase, URL-safe (`a-z`, `0-9`, `_`, `-`), unique, not reserved (`config/portfolio.php`).

### Multi-tenant isolation

- Every module table has `user_id`
- Admin APIs always scope queries to `auth()->id()` via service ownership helpers
- Public portfolio resolves `User` by `username`, then loads **only** that user's **active** records

### Authenticated requests

```http
Authorization: Bearer {token}
Accept: application/json
```

## Profile Module

Authenticated user portfolio profile (multi-tenant via `user_id`).

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/profile` | Full profile + social_links + meta |
| POST | `/api/profile` | Create/update profile (upsert) |
| POST | `/api/profile/avatar` | Upload avatar (`multipart`: `image`) |
| POST | `/api/profile/cover` | Upload cover (`multipart`: `image`) |
| POST | `/api/social-links` | Upsert social links array |
| DELETE | `/api/social-links/{id}` | Delete own social link |
| POST | `/api/professional-meta` | Upsert key/value meta |

Response `data` shape:

```json
{
  "profile": { "name": "...", "title": "...", "avatar_url": "...", "cover_image_url": "..." },
  "social_links": [],
  "meta": []
}
```

Images are stored on the `public` disk (`storage/app/public`). Run `php artisan storage:link` once.

### Profile UI (Blade admin)

- Page: `/admin/profile` (`resources/views/admin/profile.blade.php`)
- Partials: `header`, `about`, `contact`, `social-links`, `professional-meta`
- JS: `resources/js/pages/profile.js` + toast helper
- Features: avatar preview/upload, completion bar, dynamic social/meta rows, save toasts

## Skills Module

Profession-agnostic skills with categories (multi-tenant via `user_id`). Supports percentage, text, or stars levels; featured flag ready for public portfolio themes.

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/categories` | List own categories (nested skills) |
| POST | `/api/categories` | Create category |
| PUT | `/api/categories/{id}` | Update category |
| DELETE | `/api/categories/{id}` | Delete category (+ cascade skills) |
| PATCH | `/api/categories/{id}/toggle` | Toggle `is_active` |
| PATCH | `/api/categories/reorder` | Body: `{ "ids": [3,1,2] }` |
| GET | `/api/skills` | List skills (`?category_id=` optional) |
| POST | `/api/skills` | Create skill |
| PUT | `/api/skills/{id}` | Update skill |
| DELETE | `/api/skills/{id}` | Delete skill |
| PATCH | `/api/skills/{id}/toggle` | Toggle `is_active` |
| PATCH | `/api/skills/reorder` | Body: `{ "ids": [...], "category_id": 1 }` |

### Tables

- `skill_categories`: `user_id`, `name`, `order`, `is_active`
- `skills`: `user_id`, `category_id`, `name`, `level`, `level_type` (`percentage` \| `text` \| `stars`), `is_featured`, `is_active`, `order`

### Skills UI (Blade admin)

- Page: `/admin/skills` (`resources/views/admin/skills.blade.php`)
- Modals: category + skill forms
- JS: `resources/js/pages/skills.js`
- Features: CRUD, active toggles, drag-and-drop reorder, empty/loading states, toasts

## Portfolio Items Module

Universal portfolio items (projects / case studies / works / research) — multi-tenant via `user_id`. Sidebar label is currently “Projects”; internal API uses `portfolio_*`.

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/portfolio-categories` | List categories |
| POST | `/api/portfolio-categories` | Create category |
| PUT | `/api/portfolio-categories/{id}` | Update category |
| DELETE | `/api/portfolio-categories/{id}` | Delete category (items keep data; `category_id` null) |
| PATCH | `/api/portfolio-categories/{id}/toggle` | Toggle `is_active` |
| PATCH | `/api/portfolio-categories/reorder` | Body: `{ "ids": [...] }` |
| GET | `/api/portfolio-items` | List items (`?category_id=` optional) |
| POST | `/api/portfolio-items` | Create item (slug auto-generated per user) |
| PUT | `/api/portfolio-items/{id}` | Update item |
| DELETE | `/api/portfolio-items/{id}` | Delete item + media files |
| PATCH | `/api/portfolio-items/{id}/toggle` | Toggle `is_active` |
| PATCH | `/api/portfolio-items/reorder` | Body: `{ "ids": [...] }` |
| POST | `/api/portfolio-items/{id}/media` | Upload images (`multipart`: `images[]`) |
| PATCH | `/api/portfolio-items/{id}/media/reorder` | Body: `{ "ids": [...] }` |
| DELETE | `/api/portfolio-media/{id}` | Delete one image |

### Tables

- `portfolio_categories`: `user_id`, `name`, `order`, `is_active`
- `portfolio_items`: `title`, `slug` (unique per user), `description`, `short_description`, `category_id`, `client_name`, `project_url`, dates, `is_featured`, `is_active`, `order`
- `portfolio_media`: `portfolio_item_id`, `file_path`, `order`

### Portfolio UI (Blade admin)

- Page: `/admin/projects` (`resources/views/admin/projects.blade.php`)
- Modals: item form + manage categories
- JS: `resources/js/pages/projects.js`
- Features: card grid, featured/inactive badges, multi-image upload, drag reorder, toasts

## Education Module

Profession-agnostic education history (degrees, diplomas, medical/law school, etc.) — multi-tenant via `user_id`.

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/educations` | List own education entries |
| POST | `/api/educations` | Create entry |
| PUT | `/api/educations/{id}` | Update entry |
| DELETE | `/api/educations/{id}` | Delete entry |
| PATCH | `/api/educations/{id}/toggle` | Toggle `is_active` |
| PATCH | `/api/educations/reorder` | Body: `{ "ids": [...] }` |

### Table

- `educations`: `user_id`, `degree`, `institution`, `field_of_study`, `location`, `start_date`, `end_date`, `is_current`, `grade`, `description`, `is_active`, `order`

When `is_current` is true, `end_date` is cleared and UI shows “Present”.

### Education UI (Blade admin)

- Page: `/admin/education` (`resources/views/admin/education.blade.php`)
- Modal: create/edit form
- JS: `resources/js/pages/education.js` (Flatpickr dates)
- Features: timeline layout, currently-studying toggle, drag reorder, toasts

## Experience Module

Profession-agnostic work history (companies, hospitals, firms, studios) — multi-tenant via `user_id`.

| Method | URI | Description |
|--------|-----|-------------|
| GET | `/api/experiences` | List own experience entries |
| POST | `/api/experiences` | Create entry |
| PUT | `/api/experiences/{id}` | Update entry |
| DELETE | `/api/experiences/{id}` | Delete entry |
| PATCH | `/api/experiences/{id}/toggle` | Toggle `is_active` |
| PATCH | `/api/experiences/reorder` | Body: `{ "ids": [...] }` |

### Table

- `experiences`: `user_id`, `title`, `organization`, `employment_type` (`full_time` \| `part_time` \| `freelance` \| `internship` \| `contract` \| `other`), `location`, `start_date`, `end_date`, `is_current`, `description`, `is_active`, `order`

When `is_current` is true, `end_date` is cleared and UI shows “Present”.

### Experience UI (Blade admin)

- Page: `/admin/experience` (`resources/views/admin/experience.blade.php`)
- Modal: create/edit form
- JS: `resources/js/pages/experience.js` (Flatpickr dates)
- Features: timeline layout, currently-working toggle, employment type, drag reorder, toasts

## Admin Endpoints

Protected by `auth:sanctum` + `admin` middleware.

| Method | URI                     | Description    |
|--------|-------------------------|----------------|
| GET    | `/api/admin/users`      | List users     |
| GET    | `/api/admin/users/{id}` | Show user      |
| PUT    | `/api/admin/users/{id}` | Update user    |
| DELETE | `/api/admin/users/{id}` | Delete user    |
| PUT    | `/api/admin/settings`   | Update admin brand colours |

## Roles

| Role    | Access                                 |
|---------|----------------------------------------|
| `admin` | Auth + admin user management API       |
| `user`  | Auth only; admin routes return **403** |

## Database (Users)

| Column       | Type    | Notes                                   |
|--------------|---------|-----------------------------------------|
| `id`         | bigint  | Primary key                             |
| `name`       | string  |                                         |
| `username`   | string  | Unique (auto-generated on API register) |
| `email`      | string  | Unique                                  |
| `password`   | string  | Hashed                                  |
| `role`       | enum    | `admin` or `user` (default: `user`)     |
| `status`     | boolean | Default `true`                          |
| `timestamps` | —       |                                         |

## Project Structure

```
app/Http/Controllers/API/
├── AuthController.php
├── ProfileController.php
├── SocialLinkController.php
├── ProfessionalMetaController.php
├── SettingController.php
└── Admin/UserController.php
app/Services/ProfileService.php
app/Models/
├── User.php
├── Profile.php
├── SocialLink.php
├── ProfessionalMeta.php
└── Setting.php
app/Http/Resources/API/
├── ProfileBundleResource.php
├── ProfileResource.php
├── SocialLinkResource.php
└── ProfessionalMetaResource.php
config/admin_navigation.php
database/migrations/*_create_settings_table.php
resources/views/
├── layouts/
│   ├── app.blade.php
│   ├── guest.blade.php
│   └── dashboard.blade.php      # Admin shell + sidebar
├── components/
│   ├── admin/sidebar.blade.php
│   └── icon.blade.php
├── admin/
│   ├── settings.blade.php       # Admin brand colours
│   └── coming-soon.blade.php
├── auth/login.blade.php
├── dashboard.blade.php
└── welcome.blade.php
resources/js/
├── config/sidebarMenu.js
├── sidebar.js
├── theme.js
├── api.js
├── auth.js
└── app.js
routes/
├── api.php
└── web.php
```

## Commands

```bash
php artisan migrate --seed
php artisan db:seed --class=AdminSeeder
php artisan route:list --path=api
npm run dev
npm run build
./vendor/bin/pint
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
