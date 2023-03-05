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
Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

// shrinkk route for the URL only for authenticated users
Route::middleware('auth:sanctum')->post('/url/shrinkk/list', [UrlController::class, 'index']);
Route::middleware('auth:sanctum')->post('/url/shrinkk/create', [UrlController::class, 'create']);
Route::middleware('auth:sanctum')->post('/url/shrinkk/delete/{code}', [UrlController::class, 'delete']);
