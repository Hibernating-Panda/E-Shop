<?php

use Illuminate\Support\Facades\Route;

Route::prefix('shop')->group(function () {
    Route::get('/', function () {
        return 'Shop routes';
    });
});