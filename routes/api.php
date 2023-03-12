<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Url\UrlController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
|
*/

// registration & login routes for the user
Route::prefix('/auth')->group(function () {
    Route::post('/register', [AuthController::class, 'createUser']);
    Route::post('/login', [AuthController::class, 'loginUser']);
});

// shrinkk route group only for authenticated users
Route::middleware('auth:sanctum')->prefix('/url/shrinkk')->group(function () {
    Route::get('/list', [UrlController::class, 'index']);
    Route::post('/create', [UrlController::class, 'create']);
    Route::delete('/delete/{code}', [UrlController::class, 'delete']);
});
