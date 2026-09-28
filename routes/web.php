<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CountyController;

Route::get('/', function () {
    return redirect()->route('cities.index');
});

Route::resource('cities', CityController::class)->except(['show']);

Route::resource('counties', CountyController::class)->except(['show']);
