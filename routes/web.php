<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ProductController;
// use App\Http\Controllers\ScanController;
use App\Http\Controllers\QrController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [ProductController::class, 'index']);

// Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');

// Route::post('/products', [ProductController::class, 'store'])->name('product.store');

// Route::get('/scan', [ScanController::class, 'show']);

Route::get('/', [QrController::class, 'index']);

Route::get('/create', [QrController::class, 'create']);

Route::post('/store', [QrController::class, 'store']);

Route::get('/scan', [QrController::class, 'scan']);
