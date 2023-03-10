<?php

use App\Http\Controllers\Url\UrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
*/
Route::get('/{code}', [UrlController::class, 'redirect']);
