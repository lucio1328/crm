<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DefaultController;
use App\Http\Controllers\Api\PaiementController;
use App\Http\Controllers\Api\StatistiqueController;
use App\Http\Controllers\Api\DetailsController;
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

Route::get('/details/clients', [DetailsController::class, 'clients']);
Route::get('/details/projets', [DetailsController::class, 'projets']);
Route::get('/details/taches', [DetailsController::class, 'taches']);
Route::get('/details/offres', [DetailsController::class, 'offres']);
Route::get('/details/factures', [DetailsController::class, 'factures']);
Route::get('/details/paiements', [DetailsController::class, 'paiements']);

Route::delete('/paiement/delete/{id}', [PaiementController::class, 'destroy'])->middleware('auth:api');
Route::put('/paiement/update/{id}', [PaiementController::class, 'update'])->middleware('auth:api');

Route::get('/default', [DefaultController::class, 'index']);