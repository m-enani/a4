<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/search', 'RestaurantController@search');
Route::get('autocomplete',array('as'=>'autocomplete','uses'=>'RestaurantController@autocomplete'));
Route::post('/add', 'RestaurantController@add');
Route::post('/remove', 'RestaurantController@remove');
Route::post('/removechoice', 'RestaurantController@removechoice');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
