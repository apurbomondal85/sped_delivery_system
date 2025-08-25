<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DeliveryZoneController;

Route::get('/', function () {
    return redirect()->route('zones.index');
});

Route::middleware('auth')->group(function () {

    // Delivery Zones
    Route::resource('zones', DeliveryZoneController::class);

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/store', [OrderController::class, 'store'])->name('store');

        // Accept / Reject
        Route::post('{order}/accept', [OrderController::class, 'accept'])->name('accept');
        Route::post('{order}/reject', [OrderController::class, 'reject'])->name('reject');
    });

    Route::get('/delivery-man', [OrderController::class, 'deliveryMan'])->name('deliveryMan');
});

require __DIR__ . '/auth.php';
