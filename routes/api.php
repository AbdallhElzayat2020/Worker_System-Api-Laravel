<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\WorkerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::group(['middleware' => 'api', 'prefix' => 'auth/admin'], function () {
    Route::get('test', [AdminController::class, 'test']);
    Route::post('register', [AdminController::class, 'register']);
    Route::post('login', [AdminController::class, 'login']);
    Route::post('refresh', [AdminController::class, 'refresh']);
    Route::post('logout', [AdminController::class, 'logout']);
});



Route::group(['middleware' => 'api', 'prefix' => 'auth/worker'], function () {
    Route::get('test', [WorkerController::class, 'test']);
    Route::post('register', [WorkerController::class, 'register']);
    Route::post('login', [WorkerController::class, 'login']);
    Route::post('refresh', [WorkerController::class, 'refresh']);
    Route::post('logout', [WorkerController::class, 'logout']);
});


Route::group(['middleware' => 'api', 'prefix' => 'auth/client'], function () {
    Route::get('test', [ClientController::class, 'test']);
    Route::post('register', [ClientController::class, 'register']);
    Route::post('login', [ClientController::class, 'login']);
    Route::post('refresh', [ClientController::class, 'refresh']);
    Route::post('logout', [ClientController::class, 'logout']);
});
