<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AreaController;

Route::get('/getAllAreas', [AreaController::class, 'getAllAreas']);

Route::post('/createArea', [AreaController::class, 'createArea']);