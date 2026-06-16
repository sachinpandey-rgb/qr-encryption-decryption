<?php

return [

    'secret_key' => env('QR_SECRET_KEY'),

    'expiry_days' => (int) env('QR_EXPIRY_DAYS', 365),

    'one_time_scan' => env('QR_ONE_TIME_SCAN', false),

    'verify_base_url' => rtrim(env('QR_VERIFY_BASE_URL', 'https://verify.digitathya.com'), '/'),

    'sign_secret' => env('QR_SIGN_SECRET'),

    'legacy_format' => (int) env('QR_LEGACY_FORMAT', 4),

    'token_bytes' => (int) env('QR_TOKEN_BYTES', 32),

    'app_name' => env('QR_APP_NAME', 'DigiTathya'),

    'app_scheme' => env('QR_APP_SCHEME', 'digitathya'),

    'app_package' => env('QR_APP_PACKAGE', 'com.digitathya'),

    'play_store_url' => env(
        'QR_PLAY_STORE_URL',
        'https://play.google.com/store/apps/details?id=com.digitathya'
    ),

    /*
    | Android App Links — replace SHA256 fingerprint with your release keystore hash.
    | Generate: keytool -list -v -keystore your-release.keystore -alias your-alias
    */
    'android_assetlinks_sha256' => env('QR_ANDROID_ASSETLINKS_SHA256', ''),

];
