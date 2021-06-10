<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes([
    'register' => false, 
    'verify' => true
]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// data to be shared in site
Route::get('/api/list/settings', 'SettingController@list_api');

Route::group(['middleware' => ['auth', 'admin']], function () {
    // translations
    Route::post('/translate/{groupKey}', 'TranslateController@postPublish');
    
    // users
    Route::get('/admins', 'UserController@index_admins');
    Route::get('/clients', 'UserController@index_clients');
    Route::get('/api/users', 'UserController@index_api');
    Route::resource('/users', 'UserController')->only(['store', 'update', 'destroy']);
    
    // pages
    Route::resource('/pages', 'PageController')->only(['index', 'store', 'update', 'destroy']);
    Route::get('/api/pages', 'PageController@index_api');

    // notes
    Route::resource('/notes', 'NoteController')->only(['index', 'store', 'update', 'destroy']);
    Route::get('/api/notes', 'NoteController@index_api');

    // locales
    Route::get('/locales', 'PageController@getLocales'); 

    Route::get('/test', 'UserController@test'); 
});

// Settings
Route::group([
    'as'     => 'settings.',
    'prefix' => 'settings',
], function () {
    Route::get('/', ['uses' => 'SettingController@index',        'as' => 'index']);
    Route::post('/', ['uses' => 'SettingController@store',        'as' => 'store']);
    Route::put('/', ['uses' => 'SettingController@update',       'as' => 'update']);
    Route::delete('{id}', ['uses' => 'SettingController@delete',       'as' => 'delete']);
    Route::get('{id}/move_up', ['uses' => 'SettingController@move_up',      'as' => 'move_up']);
    Route::get('{id}/move_down', ['uses' => 'SettingController@move_down',    'as' => 'move_down']);
    Route::put('{id}/delete_value', ['uses' => 'SettingController@delete_value', 'as' => 'delete_value']);
});

Route::group(['middleware' => ['auth','super_admin']], function () {
    Route::get('/artisan/{command}', function($command) {
        Artisan::call($command);
        return "called ".$command;
    });
});
