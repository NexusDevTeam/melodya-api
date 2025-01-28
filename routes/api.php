<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    include 'auth/routes.php';
    include 'user/routes.php';
});
