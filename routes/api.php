<?php

use App\Http\Controllers\Api\DeviceTokenController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('device-token', [DeviceTokenController::class, 'store']);
    Route::delete('device-token', [DeviceTokenController::class, 'destroy']);
});
