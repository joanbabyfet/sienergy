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

Route::post('login', 'ctl_login@login')->name('api.login.login');
Route::get('ping', 'ctl_common@ping')->name('api.common.ping');
Route::get('ip', 'ctl_common@ip')->name('api.common.ip');
Route::post('refresh_token', 'ctl_login@refresh_token')->name('api.login.refresh_token'); //刷新认证token
Route::get('h5', 'ctl_h5@detail')->name('api.h5.detail');

//目前改用jwt.auth,通过auth中间件并指定api守卫
Route::group(['middleware' => ['jwt.auth']], function() {
    Route::post('logout', 'ctl_login@logout')->name('api.login.logout'); //退出
    Route::post('get_userinfo', 'ctl_user@get_userinfo')->name('api.user.get_userinfo'); //用户信息

    Route::group(['middleware' => ['role:2']], function (){
        Route::match(['GET', 'POST'], 'example', 'ctl_example@index')->name('api.example.index');
        Route::match(['GET', 'POST'], 'example/detail', 'ctl_example@detail')->name('api.example.detail');
        Route::match(['GET', 'POST'], 'example/add', 'ctl_example@add')->name('api.example.add');
        Route::match(['GET', 'POST'], 'example/edit', 'ctl_example@edit')->name('api.example.edit');
        Route::match(['GET', 'POST'], 'example/delete', 'ctl_example@delete')->name('api.example.delete');
        Route::match(['GET', 'POST'], 'example/enable', 'ctl_example@enable')->name('api.example.enable');
        Route::match(['GET', 'POST'], 'example/disable', 'ctl_example@disable')->name('api.example.disable');
        Route::match(['GET', 'POST'], 'news', 'ctl_news@index')->name('api.news.index');
        Route::match(['GET', 'POST'], 'test', 'ctl_test@index')->name('api.test.index');
        Route::match(['GET', 'POST'], 'update_stock', 'ctl_test@update_stock')->name('api.test.update_stock');
    });
});
