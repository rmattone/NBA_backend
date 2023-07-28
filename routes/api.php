<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Cemiterio\PeopleController;
use App\Http\Controllers\Users\UsersController;
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

Route::get('/user', [UsersController::class, 'getUser'])->middleware('auth:api');
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'loginCemiterio']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::group(['prefix' => 'people'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/', [PeopleController::class, 'store']);
        Route::post('/{personId}/update', [PeopleController::class, 'update']);
        Route::delete('/{personId}', [PeopleController::class, 'destroy']);
    });
    Route::get('/', [PeopleController::class, 'index']);
    Route::get('/{personId}', [PeopleController::class, 'show']);
});
