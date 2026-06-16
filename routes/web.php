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

Route::get('/scan', [QrController::class, 'scan']);

Route::get('/api/verify', [QrController::class, 'verify']);

Route::post('/api/tokens/{token}/revoke', [QrVerifyController::class, 'revokeToken'])
    ->where('token', '[A-Za-z0-9_-]+');
