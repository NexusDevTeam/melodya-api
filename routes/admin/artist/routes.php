<?php

use Illuminate\Support\Facades\Route;

Route::prefix('artist')->group(function () {
    Route::get('/', 'App\Http\Controllers\Api\V1\Artist\ArtistController@index')->middleware('permission:artist_list');

    Route::get('/{id}', 'App\Http\Controllers\Api\V1\Artist\ArtistController@show')->middleware('permission:artist_list');

    Route::post('/', 'App\Http\Controllers\Api\V1\Artist\ArtistController@store')->middleware('permission:artist_create');

    Route::put('/{id}', 'App\Http\Controllers\Api\V1\Artist\ArtistController@update')->middleware('permission:artist_edit');

    Route::delete('/{id}', 'App\Http\Controllers\Api\V1\Artist\ArtistController@destroy')->middleware('permission:artist_delete');
});
