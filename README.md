# MyPortfolio

A Laravel SaaS portfolio platform with **API-first** Sanctum authentication and a custom Blade + Tailwind UI.

## Tech Stack

- **PHP** 8.4+
- **Laravel** 13.x
- **Laravel Sanctum** (API token auth)
- **Blade + Tailwind CSS + Vite** (custom UI)
- **MySQL** (Laragon recommended)

## Features

- API authentication: register, login, logout, me
- Custom login page + dashboard (token stored in `localStorage`)
- Sanctum personal access tokens
- Roles: `admin`, `user`
- Admin API for user management (list, show, update, delete)
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
| Password | `password`          |
| Role     | `admin`             |

```bash
php artisan db:seed --class=AdminSeeder
```

## Frontend Pages

| URL          | Description                                      |
|--------------|--------------------------------------------------|
| `/`          | Welcome page                                     |
| `/login`     | Split-screen login UI → `POST /api/login`        |
| `/dashboard` | Dashboard UI → `GET /api/me` (requires token)    |

Auth flow (client-side):

1. Login stores Sanctum token in `localStorage` (`auth_token`)
2. Dashboard loads user via `Authorization: Bearer {token}`
3. Missing/invalid token redirects to `/login`
4. Logout calls `POST /api/logout`, clears token, redirects to `/login`

JS modules:

```
resources/js/
├── api.js              # fetch wrapper + token helpers
├── auth.js             # login / logout / me
├── app.js              # page bootstrap
└── pages/
    ├── login.js
    └── dashboard.js
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

| Method | URI             | Auth | Description             |
|--------|-----------------|------|-------------------------|
| POST   | `/api/register` | No   | Register + return token |
| POST   | `/api/login`    | No   | Login + return token    |
| POST   | `/api/logout`   | Yes  | Revoke current token    |
| GET    | `/api/me`       | Yes  | Current user            |

### Login (example)

```http
POST /api/login
Content-Type: application/json
Accept: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

### Authenticated requests

```http
Authorization: Bearer {token}
Accept: application/json
```

## Admin Endpoints

Protected by `auth:sanctum` + `admin` middleware.

| Method | URI                     | Description    |
|--------|-------------------------|----------------|
| GET    | `/api/admin/users`      | List users     |
| GET    | `/api/admin/users/{id}` | Show user      |
| PUT    | `/api/admin/users/{id}` | Update user    |
| DELETE | `/api/admin/users/{id}` | Delete user    |

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
└── Admin/UserController.php
app/Http/Middleware/AdminMiddleware.php
app/Http/Responses/ApiResponse.php
resources/views/
├── layouts/app.blade.php
├── auth/login.blade.php
├── dashboard.blade.php
└── welcome.blade.php
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
