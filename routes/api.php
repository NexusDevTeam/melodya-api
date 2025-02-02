<?php

use Illuminate\Support\Facades\Route;

include 'unauth/routes.php';
Route::middleware(['auth:sanctum'])
    ->group(function () {
        include 'auth/routes.php';

        include 'admin/routes.php';
    });
