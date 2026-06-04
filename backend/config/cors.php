<?php

return [
    'paths' => ['api/*', 'up'],
    'allowed_methods' => ['*'],

    /*
     * Restrict to the frontend origin(s) by default instead of "*".
     * Set CORS_ALLOWED_ORIGINS (comma-separated) to allow more; falls back
     * to FRONTEND_URL, then localhost dev. Use "*" explicitly to allow all.
     */
    'allowed_origins' => array_values(array_filter(array_map('trim', explode(
        ',',
        env('CORS_ALLOWED_ORIGINS', env('FRONTEND_URL', 'http://localhost:5173'))
    )))),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 86400,
    'supports_credentials' => false,
];
