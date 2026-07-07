# simli-backend (Laravel API)

Mints short-lived Simli session tokens so the browser never sees the raw `SIMLI_API_KEY` or
faceID. One endpoint:

```
POST /api/simli/session  ->  { "session_token": "..." }
```

These are the Simli-specific files only, in their correct Laravel paths.

## Prerequisite
PHP 8.2+ and Composer. On Windows use Laravel Herd (bundles PHP + Composer).

## Option A — Run standalone (local dev)
```bash
composer create-project laravel/laravel simli-backend-app
cd simli-backend-app
php artisan install:api        # Laravel 11/12 has no api routes by default
# copy the files from this folder over the fresh app
# merge the 'simli' block into config/services.php
# add Simli vars to .env (see .env.example)
php artisan serve              # http://localhost:8000

curl -X POST http://localhost:8000/api/simli/session
# -> {"session_token":"..."}   (or 502 if keys are missing/invalid)
```

## Option B — Merge into the LMS's Laravel app (production path)
Copy `SimliService.php` + `SimliController.php` (same paths), append the route line to the LMS
`routes/api.php`, merge the `simli` block into `config/services.php`, and add the Simli vars to
the LMS `.env`. Same-origin => CORS is a no-op.
