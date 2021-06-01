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

Route::group(['prefix' => 'v1'], function(){
    Route::post('login', 'AuthController@login');
});

Route::group(['prefix' => 'v1', 'middleware' => 'auth:api'], function(){
    Route::post('/user/email', 'UserController@email');
    Route::get('/list/pages', 'PageController@list_api'); 

    Route::get('/notes', 'NoteController@index_user'); 
    Route::post('/notes', 'NoteController@store');    
    Route::get('/notes/{reference}', 'NoteController@show'); 
    Route::patch('/notes/{id}', 'NoteController@update'); 
    Route::delete('/notes/{id}', 'NoteController@destroy');

    Route::get('/user/{user_id}/relation/{attribute}', 'UserRelationController@index'); 
    Route::post('/user/relation/add', 'UserRelationController@add'); 
    Route::post('/user/relation/remove', 'UserRelationController@remove'); 

    Route::get('/user/{user_id}/settings', 'UserSettingController@show'); 
    Route::post('/user/settings', 'UserSettingController@update'); 
});