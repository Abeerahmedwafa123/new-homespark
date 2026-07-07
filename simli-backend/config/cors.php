<?php

/*
|--------------------------------------------------------------------------
| Cross-Origin Resource Sharing (CORS) Configuration
|--------------------------------------------------------------------------
|
| The standalone Vue SPA runs on a different origin (e.g. :5173) than this
| API (:8000), so the browser needs CORS. Allowed origins are driven by the
| FRONTEND_ORIGINS env var (comma-separated) so it is configurable per env.
|
| After merging the frontend same-origin into the LMS, CORS becomes a no-op
| (same-origin requests skip it) — no code change needed.
|
*/

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => array_values(array_filter(array_map(
        'trim',
        explode(',', env('FRONTEND_ORIGINS', 'http://localhost:5173'))
    ))),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
