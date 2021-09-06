<?php

use HollyIT\LaravelStatic\Http\Controllers\StaticController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

Route::get(Config::get('laravel-static.lazy_routes', '_lede_static').'/{vendor}/{library}/{path}',
    [StaticController::class, 'handle'])
    ->name('laravel_static_path')
    ->whereAlphaNumeric('vendor')
    ->whereAlphaNumeric('library')
    ->where('path', '.*');
