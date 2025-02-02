<?php

use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['role:super_admin|admin'])->group(function () {
    include 'user/routes.php';

    include 'artist/routes.php';
});
