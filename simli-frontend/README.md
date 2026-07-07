# simli-frontend (Vue 3 + Vite)

Vue 3 port of the original Next.js/React Simli avatar app. Streams audio to a Simli avatar over
WebRTC. The Simli secret is **not** here — the app asks the Laravel backend for a short-lived
`session_token`.

## Run (standalone dev)

```bash
npm install
cp .env.example .env   # VITE_API_BASE_URL=http://localhost:8000
npm run dev            # http://localhost:5173
```

The backend (`../simli-backend`) must be running for "Initialize Simli" to work.

## Structure (all portable — drop into the LMS Vue frontend)
- `src/components/SimliAvatar.vue` — the whole feature. Props: `apiBaseUrl`, `defaultAudioUrl`.
- `src/lib/audio.js` — `downsampleAndChunkAudioPCM16_16kHz` (pure Web Audio API).
- `src/lib/simliApi.js` — fetches the session token from the backend.

### Merge into the LMS
Copy `src/components/SimliAvatar.vue` + `src/lib/*` into the LMS frontend, add `simli-client` to
its dependencies, then mount: `<SimliAvatar :api-base-url="lmsApiBase" />`.

## Config
- `VITE_API_BASE_URL` — backend origin (see `.env.example`). Never hardcode it.
