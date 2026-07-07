<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | When merging into the LMS, copy ONLY the 'simli' block below into the
    | LMS's existing config/services.php (do not overwrite its other keys).
    |
    */

    'simli' => [
        'api_key'            => env('SIMLI_API_KEY'),
        'face_id'            => env('SIMLI_FACE_ID'),
        'base_url'           => env('SIMLI_BASE_URL', 'https://api.simli.ai'),
        'model'              => env('SIMLI_MODEL', 'fasttalk'),
        'max_session_length' => env('SIMLI_MAX_SESSION_LENGTH', 3600),
        'max_idle_time'      => env('SIMLI_MAX_IDLE_TIME', 600),
    ],

];
