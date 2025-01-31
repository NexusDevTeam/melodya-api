<?php

use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    Route::get('logout', 'App\Http\Controllers\Api\V1\Auth\AuthController@logout');

    Route::get('profile', 'App\Http\Controllers\Api\V1\Auth\AuthController@profile');

    Route::put('edit-profile', 'App\Http\Controllers\Api\V1\Auth\AuthController@editProfile');

    Route::put('upgrade-to-artist', 'App\Http\Controllers\Api\V1\Auth\AuthController@upgradeToArtist');
});
