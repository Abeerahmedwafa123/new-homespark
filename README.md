# new-homespark — Simli Avatar (Vue 3 SPA + Laravel API)

Simli talking-avatar feature, migrated from the original Next.js/React prototype to a
**Vue 3 SPA** + **PHP Laravel API**. Built to be **merged into the LMS codebase** (Vue into
the LMS frontend, Laravel files into the LMS backend) — no iframe.

## Repo layout

```
simli-frontend/     # Vite + Vue 3 SPA (Tailwind v4)
simli-backend/      # Laravel API module (mints Simli session tokens)
```

## How it works (the important part)

The Simli **API key and faceID are never sent to the browser**. The Vue app asks the Laravel
backend for a short-lived `session_token`, then initializes `SimliClient` with
`{ apiKey: "", session_token, faceID: "" }`. The backend mints the token via
`POST https://api.simli.ai/startAudioToVideoSession` using secrets from its `.env`.

## Prerequisites
- Node.js 18+ (frontend)
- PHP 8.2+ and Composer (backend). On Windows, Laravel Herd is easiest.

## Run locally (two terminals)

**Backend** (`simli-backend` — see its README for fresh-Laravel steps):
```bash
php artisan install:api
# set SIMLI_API_KEY + SIMLI_FACE_ID + FRONTEND_ORIGINS in .env
php artisan serve            # http://localhost:8000
```

**Frontend** (`simli-frontend`):
```bash
cd simli-frontend
npm install
cp .env.example .env         # VITE_API_BASE_URL=http://localhost:8000
npm run dev                  # http://localhost:5173
```

## Merging into the LMS
- **Frontend:** copy `simli-frontend/src/components/SimliAvatar.vue` + `src/lib/*` into the LMS
  Vue app, add `simli-client` to its deps, mount `<SimliAvatar :api-base-url="lmsApiBase" />`.
- **Backend:** copy `simli-backend/app/Services/SimliService.php` +
  `app/Http/Controllers/SimliController.php`, add the route to `routes/api.php`, merge the `simli`
  block into `config/services.php`, add the Simli env vars. Same-origin after merge => CORS no-op.

## Notes
- Assumes the LMS frontend is Vue and its backend is Laravel.
- UI is intentionally still 1:1 with the original prototype — UX redesign is a planned next phase.
- Set real Simli secrets only in the **backend** `.env` — never in the frontend.
- Sample audio `simli-frontend/public/audio/test.mp3` is not committed (binary). Drop any
  16 kHz-friendly audio there for the default test, or point the input at another URL.
