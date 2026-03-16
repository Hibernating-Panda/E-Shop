<?php

use Illuminate\Support\Facades\Route;

Route::prefix('delivery')->group(function () {
    Route::get('/', function () {
        return 'Delivery routes';
    });
});