<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DefaultController;
use App\Http\Controllers\Api\StatistiqueController;
use Illuminate\Http\Request;

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

Route::group(['namespace' => 'App\Api\v1\Controllers'], function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('users', ['uses' => 'UserController@index']);
    });
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::get('/statistiques', [StatistiqueController::class, 'index'])->middleware('auth:api');
Route::get('/default', [DefaultController::class, 'index']);