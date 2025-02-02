<?php

use Illuminate\Support\Facades\Route;

Route::prefix('artist')->middleware('role:artist')->group(function () {
    Route::get('/', 'App\Http\Controllers\Api\V1\Artist\ArtistController@profileArtist')->middleware('permission:artist_list');

    Route::post('/', 'App\Http\Controllers\Api\V1\Artist\ArtistController@store')->middleware('permission:artist_create');

    Route::put('/', 'App\Http\Controllers\Api\V1\Artist\ArtistController@update')->middleware('permission:artist_edit');
});
