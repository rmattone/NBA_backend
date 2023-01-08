<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PostController;
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
Route::group(['prefix' => 'cemiterio', 'middleware' => ['cors', 'json.response']], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', [AuthController::class, 'loginCemiterio']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });
    
    Route::group(['middleware' => 'auth:api'], function () {
    });
});