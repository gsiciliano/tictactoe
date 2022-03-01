<?php

use Illuminate\Http\Request;
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

Route::prefix('v1')->middleware('passport')->group(function()
{
    Route::get('games', '\App\Http\Controllers\GameController@index');
    Route::get('games/{uuid}', '\App\Http\Controllers\GameController@find');
    Route::post('games', '\App\Http\Controllers\GameController@store');

    Route::get('games/{uuid}/turns', '\App\Http\Controllers\TurnController@index');
    Route::post('games/{uuid}/turns', '\App\Http\Controllers\TurnController@store');
});
