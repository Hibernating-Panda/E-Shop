<?php

use Illuminate\Support\Facades\Route;

Route::prefix('customer')->group(function () {
    Route::get('/', function () {
        return 'Customer routes';
    });
});