<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', [AuthController::class, 'user']);      // Get authenticated user details
        Route::post('/logout', [AuthController::class, 'logout']); // User logout
    });
    Route::post('/register', [AuthController::class, 'register']); // User registration
    Route::post('/login', [AuthController::class, 'login']);       // User login
});


//Route::get('/user', [AuthController::class, 'user']);       // Get User
