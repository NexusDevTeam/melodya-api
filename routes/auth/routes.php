<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::get('/token-information', 'App\Http\Controllers\Api\V1\Auth\AuthController@tokenInformation');

    Route::post('/user-register', 'App\Http\Controllers\Api\V1\Auth\AuthController@userRegister');

    Route::post('/user-sync', 'App\Http\Controllers\Api\V1\Auth\AuthController@userSync');
});
