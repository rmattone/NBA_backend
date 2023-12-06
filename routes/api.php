<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NBACustomStatisticsController;
use App\Http\Controllers\NBAPlayerController;
use App\Http\Controllers\NBATeamsController;
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
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/register', [AuthController::class, 'register']);
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'loginCemiterio']);
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

Route::group(['prefix' => 'nba'], function () {
    Route::group(['prefix' => 'teams'], function () {
        Route::get('/', [NBATeamsController::class, 'index']);
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/{teamId}', [NBATeamsController::class, 'infos']);
        });
    });
    Route::group(['prefix' => 'players'], function () {
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/byteam', [NBATeamsController::class, 'players']);
            Route::get('/{playerId}', [NBAPlayerController::class, 'index']);
        });
    });
    Route::group(['prefix' => 'custom'], function () {
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/first-buckets', [NBACustomStatisticsController::class, 'firstBuckets']);
        });
    });
});
