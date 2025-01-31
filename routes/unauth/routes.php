<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\Auth\AuthController;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

 Route::prefix('email')->group(function () {
    Route::get('verify', function () {
        return response()->json(['message' => 'Verifique seu e-mail para verificar sua conta.'], 409);
    })->middleware('auth')->name('verification.notice');

    Route::get('verify/{id}/{hash}', [AuthController::class, 'confirmRegister'])
        ->middleware(['signed'])
        ->name('verification.verify');

    Route::post('resend', [AuthController::class, 'resendConfirmRegister'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');
});
