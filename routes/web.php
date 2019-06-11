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

Route::group(['middleware' => ['proxy']], function () {
    Route::get('/', 'UserController@login');
});

Route::group(['middleware' => ['role']], function () {
    Route::get('home', 'UserController@index');
});

//任务管理
Route::group(['middleware' => ['checkauth']], function () {

    Route::get('task/list','TaskController@list');//任务列表页面
    Route::get('task/piliang','TaskController@piliang'); //批量提交任务页面
    Route::post('task/piliang','TaskController@dopiliang'); //批量提交任务接口
    Route::get('task/update','TaskController@update');  //修改任务页面
    Route::post('task/update','TaskController@doupdate'); //修改任务接口
    Route::post('task/delete','TaskController@delete');//删除任务接口
    Route::post('task/updatestatus','TaskController@updatestatus');//修改任务状态接口
    Route::post('task/updatestatus_many','TaskController@updatestatus_many');//修改任务状态接口
    Route::post('task/delete_many','TaskController@delete_many');//批量删除任务接口

    Route::get('task/one','TaskController@one');  //增加一个任务
    Route::post('task/create','TaskController@docreate');//创建一个任务


//任务导出excel
    Route::get('excel/export','ExcelController@task');


    Route::get('role','RoleController@index');
    Route::get('role/create','RoleController@create');
    Route::get('role/update','RoleController@update');
    Route::get('role/user/update','RoleController@userupdate');
    Route::post('role/user/update','RoleController@douserupdate');

    Route::get('user', 'UserController@list');
    Route::get('user/hasrecharge', 'UserController@hasrecharge');
    Route::get('user/recharge', 'UserController@recharge');
    Route::post('user/recharge', 'UserController@dorecharge');
    Route::get('user/info', 'UserController@info');
    Route::get('user/update', 'UserController@update');

    Route::get('permission','PermissionController@index');
    Route::get('permission/create','PermissionController@create');
    Route::post('permission/create','PermissionController@docreate');

    //关键词列表
    Route::get('keyword/today/{time}', 'KeywordController@today');
    Route::get('keyword/{id}', 'KeywordController@index');
    Route::get('keyword/history/{id}', 'KeywordController@history');




    //代理权限
    Route::get('proxy/system/{id}','ProxyController@system');
    Route::post('proxy/system','ProxyController@dosystem');
    Route::get('proxy/recharge/{id}','ProxyController@recharge');
    Route::post('proxy/recharge','ProxyController@dorecharge');
    Route::get('proxy/user/{id}','ProxyController@user');
    Route::post('proxy/user/mark/update','ProxyController@usermarkupdate');



    Route::get('pc', 'RankController@pc');
    Route::get('pc/create', 'RankController@pccreate');
    Route::get('move', 'RankController@move');
    Route::get('move/create', 'RankController@movecreate');
    Route::get('recharge', 'RechargeController@index');
    Route::get('buy', 'RechargeController@buy');
    Route::get('search', 'SearchController@index');
    Route::get('baidu/m/search', 'SearchController@baidu_move');
    Route::get('list', 'AdminController@show');
    Route::get('getrank/{id}', 'HomeController@index');
    Route::get('youhua/{id}', 'RankController@youhua');
    Route::get('zzz', 'RankController@zzz');
    Route::get('baidu', 'BaiduController@pc');
    Route::get('paimin', 'PaiminController@index');
});






//登陆注册路由
Route::get('logout', 'UserController@logout');
Route::get('dologin','UserController@dologin');
Route::get('regist', 'UserController@regist');
Route::get('doregist', 'UserController@doregist');
//验证码路由
Route::get('/captcha', 'CaptchaController@captcha');








