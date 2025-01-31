<?php

use Illuminate\Support\Facades\Route;

include 'unauth/routes.php';
Route::middleware(['auth:sanctum'])
    ->group(function () {
        include 'auth/routes.php';

        Route::middleware(['role:artist'])
            ->group(function () {
                include 'auth/artist/routes.php';
            });

        Route::prefix('admin')
            ->middleware(['role:admin'])
            ->group(function () {
                include 'admin/user/routes.php';
                include 'admin/artist/routes.php';
            });
    });
