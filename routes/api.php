<?php

use App\Http\Controllers\LugaresController;
use App\Http\Controllers\OpinionesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\lugares;

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

Route::post('/euclides', [OpinionesController::class, 'euclides']);

Route::post('/bayes', [OpinionesController::class, 'bayes']);

Route::resource('lugares', LugaresController::class);

Route::resource('opiniones', OpinionesController::class);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
