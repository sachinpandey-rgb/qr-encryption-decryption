<?php

return [

    'secret_key' => env('QR_SECRET_KEY'),

    'expiry_days' => (int) env('QR_EXPIRY_DAYS', 365),

    'one_time_scan' => env('QR_ONE_TIME_SCAN', false),

    'verify_base_url' => rtrim(env('QR_VERIFY_BASE_URL', 'https://verify.digitathya.com'), '/'),

    'sign_secret' => env('QR_SIGN_SECRET'),

    'legacy_format' => (int) env('QR_LEGACY_FORMAT', 4),

    'token_bytes' => (int) env('QR_TOKEN_BYTES', 32),

];
