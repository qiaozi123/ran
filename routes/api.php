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

Route::Post('login','UserController@dologin');
Route::post('/captcha/validate', 'CaptchaController@captchaValidate');
Route::post('/pc/docreate', 'RankController@pcdocreate');
Route::post('/user/update', 'UserController@updatepassword');



$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {  //User接口
    $api->group(['namespace' => 'App\Api\Controllers'], function ($api) {
        $api->post('keyword','KeywordController@index');
    });
});
