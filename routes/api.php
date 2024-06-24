<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsersController;
use App\Http\Controllers\Api\DevicesController;
use App\Http\Controllers\Api\PersonalAccessTokensController;
use App\Http\Controllers\Api\DevicesSensorsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('/v1')->group(function () {

    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to Smart Feeding API v1',
            'version' => 1,
        ]);
    })->name('v1.home');

    Route::get('/fcm', [UsersController::class, 'toFcm']);

    Route::prefix('users')->controller(UsersController::class)->group(function(){
        Route::post('/register', 'register');
        Route::post('/login', 'login');
        Route::get('/profile', 'profile')->middleware('auth:sanctum');
        Route::delete('/logout', 'logout')->middleware('auth:sanctum');
    });

    Route::prefix('/devices')->controller(DevicesController::class)->group(function() {
        Route::middleware(['auth:sanctum', 'abilities:users'])->group(function () {
            Route::get('/', [DevicesController::class, 'all']);
            Route::post('/register', [DevicesController::class, 'register']);
            Route::post('/renew', [DevicesController::class, 'renew']);
            Route::get('/details', [DevicesController::class, 'details']);
        });

        Route::prefix('/sensor')->controller(DevicesSensorsController::class)->group(function(){
            Route::post('/add', 'add')->middleware(['auth:sanctum', 'abilities:devices']);
            Route::get('/summary', 'summary')->middleware(['auth:sanctum', 'abilities:users']);
            Route::get('/current', 'current')->middleware(['auth:sanctum', 'abilities:users']);
        });
    });
});
