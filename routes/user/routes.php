<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/', 'App\Http\Controllers\Api\V1\User\UserController@index')
        ->middleware('permission:user_list');

    Route::get('/{id}', 'App\Http\Controllers\Api\V1\User\UserController@show')
        ->middleware('permission:user_list');

    Route::put('/update-profile', 'App\Http\Controllers\Api\V1\User\UserController@updateProfile')
        ->middleware('permission:user_edit');

    Route::patch('/change-role', 'App\Http\Controllers\Api\V1\User\UserController@changeRole')
        ->middleware('permission:user_edit');
});
