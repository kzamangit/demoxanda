<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('spaceship','Api\SpaceshipController');
Route::get('/heartbeat','Api\SpaceshipController@heartBeat');

Route::fallback(function(){
    return response()->json([
        'message' => 'Servie Not Found.',
        'HTTP Status Code'=>'404'
], 404);
});