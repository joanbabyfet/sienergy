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

use Illuminate\Support\Facades\Route;

Route::get('login', 'ctl_login@showLoginForm')->name('web.login.showLoginForm'); //登录页
//1个IP 1分钟只能访问60次，超过报错429 Too Many Attempts
Route::post('login', 'ctl_login@login')->name('web.login.login')->middleware('throttle:60,1');
Route::get('logout', 'ctl_login@logout')->name('web.login.logout'); //退出

Route::match(['GET', 'POST'], '/', 'ctl_index@index')->name('web.index.index');
Route::match(['GET', 'POST'], 'about', 'ctl_about@index')->name('web.about.index');
Route::match(['GET', 'POST'], 'contact', 'ctl_contact@index')->name('web.contact.index');
Route::match(['GET', 'POST'], 'feedback', 'ctl_contact@feedback')->name('web.contact.feedback');
Route::match(['GET', 'POST'], 'links', 'ctl_links@index')->name('web.links.index');
Route::match(['GET', 'POST'], 'faq', 'ctl_faq@index')->name('web.faq.index');
Route::match(['GET', 'POST'], 'news', 'ctl_news@index')->name('web.news.index');
Route::match(['GET', 'POST'], 'news/detail', 'ctl_news@detail')->name('web.news.detail');
Route::match(['GET', 'POST'], 'news/export', 'ctl_news@export_list')->name('web.news.export_list');
Route::match(['GET', 'POST'], 'products', 'ctl_products@index')->name('web.products.index');
Route::match(['GET', 'POST'], 'products_a', 'ctl_products@list_a')->name('web.products.list_a');
Route::match(['GET', 'POST'], 'products_a1', 'ctl_products@list_a1')->name('web.products.list_a1');
Route::match(['GET', 'POST'], 'products_a2', 'ctl_products@list_a2')->name('web.products.list_a2');
Route::match(['GET', 'POST'], 'products_a3', 'ctl_products@list_a3')->name('web.products.list_a3');
Route::match(['GET', 'POST'], 'products_a4', 'ctl_products@list_a4')->name('web.products.list_a4');
Route::match(['GET', 'POST'], 'products_b', 'ctl_products@list_b')->name('web.products.list_b');
Route::match(['GET', 'POST'], 'products_b1', 'ctl_products@list_b1')->name('web.products.list_b1');
Route::match(['GET', 'POST'], 'products_b2', 'ctl_products@list_b2')->name('web.products.list_b2');

//需要登录才能访问的地址
//Route::group(['middleware' => 'auth'], function () {
//    Route::group(['middleware' => ['role:32']], function (){
//    });
//});
