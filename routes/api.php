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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api')->prefix('v1')->group(function () {

    Route::resource('/real-states', 'RealStateController');
    Route::resource('users', 'UserController');
    Route::resource('/categories', 'CategoryController');
    Route::get('/categories/{id}/real-states', 'CategoryController@realStates');
    Route::delete('/photos/{id}', 'RealStatePhotoController@remove');
    Route::put('/photos/setThumb/{photoId}/{realStateId}', 'RealStatePhotoController@setThumb');

});
