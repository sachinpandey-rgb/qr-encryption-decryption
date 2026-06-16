<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrController;
use App\Http\Controllers\QrVerifyController;

Route::get('/', [QrController::class, 'index'])->name('qrcode.index');

Route::get('/create', [QrController::class, 'create']);

Route::post('/store', [QrController::class, 'store']);

Route::get('/view/{uniqueId}', [QrController::class, 'show'])
    ->where('uniqueId', '[0-9a-f-]{36}')
    ->name('qrcode.show');

Route::get('/v/{token}', [QrVerifyController::class, 'verifyByToken'])
    ->where('token', '[A-Za-z0-9_-]+');

Route::get('/qr', [QrVerifyController::class, 'verifyLegacy']);

Route::get('/verify/web', [QrVerifyController::class, 'verifyWeb']);

Route::get('.well-known/assetlinks.json', function () {
    $sha256 = config('qr.android_assetlinks_sha256');

    if (blank($sha256)) {
        return response()->json([
            'note' => 'Set QR_ANDROID_ASSETLINKS_SHA256 in .env with your app release keystore SHA-256 fingerprint.',
        ]);
    }

    $fingerprints = array_map(
        fn (string $fp) => str_replace(':', '', trim($fp)),
        explode(',', $sha256)
    );

    return response()->json([
        [
            'relation' => ['delegate_permission/common.handle_all_urls'],
            'target' => [
                'namespace' => 'android_app',
                'package_name' => config('qr.app_package'),
                'sha256_cert_fingerprints' => $fingerprints,
            ],
        ],
    ]);
});

Route::get('/scan', [QrController::class, 'scan']);

Route::get('/api/verify', [QrController::class, 'verify']);

Route::post('/api/tokens/{token}/revoke', [QrVerifyController::class, 'revokeToken'])
    ->where('token', '[A-Za-z0-9_-]+');
