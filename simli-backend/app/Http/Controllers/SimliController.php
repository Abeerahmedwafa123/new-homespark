<?php

namespace App\Http\Controllers;

use App\Services\SimliService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * SimliController — thin HTTP boundary in front of SimliService.
 *
 * Route: POST /api/simli/session  ->  { "session_token": "..." }
 * On any upstream/config failure it returns a clean 502 and logs the detail
 * server-side (never leaking the API key or Simli's raw error to the client).
 */
class SimliController extends Controller
{
    public function __construct(private readonly SimliService $simli)
    {
    }

    public function session(): JsonResponse
    {
        try {
            return response()->json([
                'session_token' => $this->simli->createSessionToken(),
            ]);
        } catch (Throwable $e) {
            Log::error('Simli session error: ' . $e->getMessage());

            return response()->json([
                'message' => 'Unable to create Simli session.',
            ], 502);
        }
    }
}
