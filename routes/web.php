<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/getAllAreas', [App\Http\Controllers\AreaController::class, 'getAllAreas']);
