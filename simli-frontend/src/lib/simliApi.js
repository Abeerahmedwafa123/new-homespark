/**
 * Thin client for the Simli backend (Laravel).
 *
 * The backend holds the secret Simli API key + faceID and mints a short-lived
 * `session_token` by calling https://api.simli.ai/startAudioToVideoSession.
 * The browser only ever sees the token — never the raw key.
 *
 * `baseUrl` is resolved in this order:
 *   1. explicit argument (e.g. a prop passed by the host LMS app)
 *   2. VITE_API_BASE_URL from the build environment
 *   3. "" (same-origin) — used when the frontend is merged into the LMS backend
 */
export function resolveApiBaseUrl(explicit) {
  if (explicit) return explicit.replace(/\/$/, "");
  const fromEnv = import.meta.env?.VITE_API_BASE_URL;
  return (fromEnv || "").replace(/\/$/, "");
}

export async function createSessionToken(baseUrl) {
  const url = `${resolveApiBaseUrl(baseUrl)}/api/simli/session`;

  const res = await fetch(url, {
    method: "POST",
    headers: { "Content-Type": "application/json", Accept: "application/json" },
    body: "{}",
  });

  if (!res.ok) {
    let detail = "";
    try {
      detail = (await res.json())?.message || "";
    } catch {
      // ignore non-JSON error bodies
    }
    throw new Error(
      `Failed to create Simli session (${res.status})${detail ? `: ${detail}` : ""}`
    );
  }

  const data = await res.json();
  if (!data?.session_token) {
    throw new Error("Backend did not return a session_token");
  }
  return data.session_token;
}
