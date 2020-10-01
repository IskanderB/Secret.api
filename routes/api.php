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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

/**
 * Creating secret
 */
Route::post('/v1/generate', 'SecretController@generate')->name('generate');

/**
 * Getting secret
 */
Route::get('/v1/secrets/{secret_key}', 'SecretController@getOne')->name('secrets');

