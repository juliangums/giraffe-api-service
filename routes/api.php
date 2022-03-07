<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\EncounterController;
use App\Http\Controllers\FavouriteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User links
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [ProfileController::class, 'user']);
        Route::put('/', [ProfileController::class, 'update']);
        Route::delete('/', [ProfileController::class, 'destroy']);
        Route::post('/image', [ProfileController::class, 'updateImage']);
    });

    // Encounters routes
    Route::resource('encounters', EncounterController::class)->except([
        'create', 'update', 'destroy',
    ]);
    Route::post('favourites', [FavouriteController::class, 'favourites']);
    Route::post('favorite/{markedIndividual}', [FavouriteController::class, 'favoriteEncounter']);
    Route::post('unfavorite/{markedIndividual}', [FavouriteController::class, 'unFavoriteEncounter']);
    Route::resource('config', ConfigController::class)->only('index');
});
Route::post('location', [LocationController::class, 'pickImageData']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/mail', [MailController::class, 'sendMail']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
