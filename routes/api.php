<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Payment\PaymentController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    //  | Authentication

    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        //  | Orders

        Route::apiResource('orders', OrderController::class);

        //  | Payments


        Route::post(
            'orders/{order}/payments',
            [PaymentController::class, 'process']
        );
    });
});