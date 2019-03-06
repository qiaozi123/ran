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

Route::get('/', 'UserController@login');
Route::get('logout', 'UserController@logout');
Route::get('dologin','UserController@dologin');
Route::get('regist', 'UserController@regist');
Route::get('doregist', 'UserController@doregist');

Route::get('/captcha', 'CaptchaController@captcha');



Route::get('home', 'AdminController@index');
Route::get('pc', 'RankController@pc');
Route::get('pc/create', 'RankController@pccreate');




Route::get('move', 'RankController@move');


Route::get('recharge', 'RechargeController@index');
Route::get('buy', 'RechargeController@buy');


Route::get('user/info', 'UserController@info');
Route::get('user/update', 'UserController@update');


Route::get('search', 'SearchController@index');


Route::get('list', 'AdminController@show');
Route::get('getrank/{id}', 'HomeController@index');
Route::get('youhua/{id}', 'RankController@youhua');
Route::get('zzz', 'RankController@zzz');
