<?php

use Illuminate\Support\Facades\Route;

Route::prefix('user')->group(function () {
    Route::get('/', 'App\Http\Controllers\Api\V1\User\UserController@index')
        ->middleware('permission:user_list');

    Route::get('/{id}', 'App\Http\Controllers\Api\V1\User\UserController@show')
        ->middleware('permission:user_list');

    Route::put('/{id}/change-role', 'App\Http\Controllers\Api\V1\User\UserController@changeRole')
        ->middleware('permission:user_edit');

    Route::put('/{id}', 'App\Http\Controllers\Api\V1\User\UserController@update')
        ->middleware('permission:user_edit');

    Route::delete('/{id}', 'App\Http\Controllers\Api\V1\User\UserController@destroy')
        ->middleware('permission:user_delete');
});
