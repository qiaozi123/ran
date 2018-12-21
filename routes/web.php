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

Route::get('/', 'RankController@index');
Route::get('list', 'RankController@show');
Route::get('getrank/{id}', 'HomeController@index');
Route::get('youhua/{id}', 'RankController@youhua');
