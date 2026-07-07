<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * SimliService — the single place that talks to Simli with the secret API key.
 *
 * It mints a short-lived `session_token` by replicating exactly what the
 * simli-client browser SDK does internally, but server-side, so the raw
 * SIMLI_API_KEY and faceID never reach the browser.
 *
 * Endpoint contract (verified against simli-client v2 dist/SimliClient.js):
 *   POST {base_url}/startAudioToVideoSession
 *   body: { faceId, isJPG:false, apiKey, syncAudio:true, handleSilence:true,
 *           maxSessionLength, maxIdleTime, model }
 *   -> { session_token: "..." }
 *
 * This class has no framework coupling beyond Http/config, so it drops
 * straight into the LMS's Laravel app.
 */
class SimliService
{
    public function createSessionToken(): string
    {
        $apiKey  = config('services.simli.api_key');
        $faceId  = config('services.simli.face_id');
        $baseUrl = rtrim((string) config('services.simli.base_url', 'https://api.simli.ai'), '/');

        if (empty($apiKey)) {
            throw new RuntimeException('SIMLI_API_KEY is not configured.');
        }
        if (empty($faceId)) {
            throw new RuntimeException('SIMLI_FACE_ID is not configured.');
        }

        $response = Http::acceptJson()
            ->timeout(15)
            ->post($baseUrl . '/startAudioToVideoSession', [
                'faceId'           => $faceId,
                'isJPG'            => false,
                'apiKey'           => $apiKey,
                'syncAudio'        => true,
                'handleSilence'    => true,
                'maxSessionLength' => (int) config('services.simli.max_session_length', 3600),
                'maxIdleTime'      => (int) config('services.simli.max_idle_time', 600),
                'model'            => (string) config('services.simli.model', 'fasttalk'),
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Simli session request failed: HTTP ' . $response->status());
        }

        $token = $response->json('session_token');

        if (empty($token)) {
            throw new RuntimeException('Simli did not return a session_token.');
        }

        return $token;
    }
}
