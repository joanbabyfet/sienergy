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

Route::get('login', 'ctl_login@showLoginForm')->name('admin.login.showLoginForm'); //登录页
//1个IP 1分钟只能访问60次，超过报错429 Too Many Attempts
Route::post('login', 'ctl_login@login')->name('admin.login.login')->middleware('throttle:60,1');
Route::get('logout', 'ctl_login@logout')->name('admin.login.logout'); //退出
Route::get('get_csrf_token', 'ctl_common@get_csrf_token')->name('admin.common.get_csrf_token'); //后台接口测试用

Route::group(['middleware' => ['auth:admin']], function (){ //中间件执行顺序由外层而内
    //主入口
    //Route::match(['GET', 'POST'], 'index', 'ctl_index@index')->name('admin.index.index');
    Route::match(['GET', 'POST'], '/', 'ctl_index@index')->name('admin.index.index');
    Route::match(['GET', 'POST'], 'admin_user/editpwd', 'ctl_admin_user@editpwd')->name('admin.admin_user.editpwd');
    Route::match(['GET', 'POST'], 'upload_chunked', 'ctl_upload@upload_chunked')->name('admin.upload.upload_chunked');
    Route::match(['GET', 'POST'], 'upload', 'ctl_upload@upload')->name('admin.upload.upload');
    Route::match(['GET', 'POST'], 'download', 'ctl_upload@download')->name('admin.upload.download');
    Route::match(['GET', 'POST'], 'translate', 'ctl_common@translate')->name('admin.common.translate');
    Route::match(['GET', 'POST'], 'wk_send', 'ctl_common@wk_send')->name('admin.common.wk_send');

    Route::group(['middleware' => ['permission:admin']], function (){
        Route::match(['GET', 'POST'], 'admin_user', 'ctl_admin_user@index')->name('admin.admin_user.index');
        Route::match(['GET', 'POST'], 'admin_user/add', 'ctl_admin_user@add')->name('admin.admin_user.add');
        Route::match(['GET', 'POST'], 'admin_user/edit', 'ctl_admin_user@edit')->name('admin.admin_user.edit');
        Route::match(['GET', 'POST'], 'admin_user/delete', 'ctl_admin_user@delete')->name('admin.admin_user.delete');
        Route::match(['GET', 'POST'], 'admin_user/enable', 'ctl_admin_user@enable')->name('admin.admin_user.enable');
        Route::match(['GET', 'POST'], 'admin_user/disable', 'ctl_admin_user@disable')->name('admin.admin_user.disable');
        Route::match(['GET', 'POST'], 'admin_user/export_list', 'ctl_admin_user@export_list')->name('admin.admin_user.export_list');
        Route::match(['GET', 'POST'], 'admin_user/purview', 'ctl_admin_user@purview')->name('admin.admin_user.purview');
        Route::match(['GET', 'POST'], 'admin_user/del_purview', 'ctl_admin_user@del_purview')->name('admin.admin_user.del_purview');
        Route::match(['GET', 'POST'], 'role', 'ctl_role@index')->name('admin.role.index');
        Route::match(['GET', 'POST'], 'role/add', 'ctl_role@add')->name('admin.role.add');
        Route::match(['GET', 'POST'], 'role/edit', 'ctl_role@edit')->name('admin.role.edit');
        Route::match(['GET', 'POST'], 'role/delete', 'ctl_role@delete')->name('admin.role.delete');
        Route::match(['GET', 'POST'], 'role/export_list', 'ctl_role@export_list')->name('admin.role.export_list');
        Route::match(['GET', 'POST'], 'permission', 'ctl_permission@index')->name('admin.permission.index');
        Route::match(['GET', 'POST'], 'permission/add', 'ctl_permission@add')->name('admin.permission.add');
        Route::match(['GET', 'POST'], 'permission/edit', 'ctl_permission@edit')->name('admin.permission.edit');
        Route::match(['GET', 'POST'], 'permission/delete', 'ctl_permission@delete')->name('admin.permission.delete');
        Route::match(['GET', 'POST'], 'permission/export_list', 'ctl_permission@export_list')->name('admin.permission.export_list');
        Route::match(['GET', 'POST'], 'permission_group', 'ctl_permission_group@index')->name('admin.permission_group.index');
        Route::match(['GET', 'POST'], 'permission_group/add', 'ctl_permission_group@add')->name('admin.permission_group.add');
        Route::match(['GET', 'POST'], 'permission_group/edit', 'ctl_permission_group@edit')->name('admin.permission_group.edit');
        Route::match(['GET', 'POST'], 'permission_group/delete', 'ctl_permission_group@delete')->name('admin.permission_group.delete');
        Route::match(['GET', 'POST'], 'permission_group/export_list', 'ctl_permission_group@export_list')->name('admin.permission_group.export_list');
        Route::match(['GET', 'POST'], 'navigation', 'ctl_navigation@index')->name('admin.navigation.index');
        Route::match(['GET', 'POST'], 'navigation/add', 'ctl_navigation@add')->name('admin.navigation.add');
        Route::match(['GET', 'POST'], 'navigation/edit', 'ctl_navigation@edit')->name('admin.navigation.edit');
        Route::match(['GET', 'POST'], 'navigation/delete', 'ctl_navigation@delete')->name('admin.navigation.delete');
        Route::match(['GET', 'POST'], 'admin_user_login', 'ctl_admin_user_login@index')->name('admin.admin_user_login.index');
        Route::match(['GET', 'POST'], 'admin_user_login/delete', 'ctl_admin_user_login@delete')->name('admin.admin_user_login.delete');
        Route::match(['GET', 'POST'], 'admin_user_login/export_list', 'ctl_admin_user_login@export_list')->name('admin.admin_user_login.export_list');
        Route::match(['GET', 'POST'], 'admin_user_oplog', 'ctl_admin_user_oplog@index')->name('admin.admin_user_oplog.index');
        Route::match(['GET', 'POST'], 'admin_user_oplog/delete', 'ctl_admin_user_oplog@delete')->name('admin.admin_user_oplog.delete');
        Route::match(['GET', 'POST'], 'admin_user_oplog/export_list', 'ctl_admin_user_oplog@export_list')->name('admin.admin_user_oplog.export_list');
        Route::match(['GET', 'POST'], 'member_login', 'ctl_member_login@index')->name('admin.member_login.index');
        Route::match(['GET', 'POST'], 'member_login/delete', 'ctl_member_login@delete')->name('admin.member_login.delete');
        Route::match(['GET', 'POST'], 'member_login/export_list', 'ctl_member_login@export_list')->name('admin.member_login.export_list');
        Route::match(['GET', 'POST'], 'api_req_log', 'ctl_api_req_log@index')->name('admin.api_req_log.index');
        Route::match(['GET', 'POST'], 'api_req_log/delete', 'ctl_api_req_log@delete')->name('admin.api_req_log.delete');
        Route::match(['GET', 'POST'], 'api_req_log/export_list', 'ctl_api_req_log@export_list')->name('admin.api_req_log.export_list');
        Route::match(['GET', 'POST'], 'redis_info', 'ctl_cache@redis_info')->name('admin.cache.redis_info');
        Route::match(['GET', 'POST'], 'redis_keys', 'ctl_cache@redis_keys')->name('admin.cache.redis_keys');
        Route::match(['GET', 'POST'], 'cache/delete', 'ctl_cache@delete')->name('admin.cache.delete');
        Route::match(['GET', 'POST'], 'cache/detail', 'ctl_cache@detail')->name('admin.cache.detail');
        Route::match(['GET', 'POST'], 'config', 'ctl_config@index')->name('admin.config.index');
        Route::match(['GET', 'POST'], 'config/add', 'ctl_config@add')->name('admin.config.add');
        Route::match(['GET', 'POST'], 'config/edit', 'ctl_config@edit')->name('admin.config.edit');
        Route::match(['GET', 'POST'], 'config/delete', 'ctl_config@delete')->name('admin.config.delete');
        Route::match(['GET', 'POST'], 'config/export_list', 'ctl_config@export_list')->name('admin.config.export_list');
        Route::match(['GET', 'POST'], 'member', 'ctl_member@index')->name('admin.member.index');
        Route::match(['GET', 'POST'], 'member/edit', 'ctl_member@edit')->name('admin.member.edit');
        Route::match(['GET', 'POST'], 'member/enable', 'ctl_member@enable')->name('admin.member.enable');
        Route::match(['GET', 'POST'], 'member/disable', 'ctl_member@disable')->name('admin.member.disable');
        Route::match(['GET', 'POST'], 'member/export_list', 'ctl_member@export_list')->name('admin.member.export_list');
        Route::match(['GET', 'POST'], 'member/import', 'ctl_member@import')->name('admin.member.import');
        Route::match(['GET', 'POST'], 'member_level', 'ctl_member_level@index')->name('admin.member_level.index');
        Route::match(['GET', 'POST'], 'member_level/add', 'ctl_member_level@add')->name('admin.member_level.add');
        Route::match(['GET', 'POST'], 'member_level/edit', 'ctl_member_level@edit')->name('admin.member_level.edit');
        Route::match(['GET', 'POST'], 'member_level/delete', 'ctl_member_level@delete')->name('admin.member_level.delete');
        Route::match(['GET', 'POST'], 'member_level/export_list', 'ctl_member_level@export_list')->name('admin.member_level.export_list');
        Route::match(['GET', 'POST'], 'h5', 'ctl_h5@index')->name('admin.h5.index');
        Route::match(['GET', 'POST'], 'h5/add', 'ctl_h5@add')->name('admin.h5.add');
        Route::match(['GET', 'POST'], 'h5/edit', 'ctl_h5@edit')->name('admin.h5.edit');
        Route::match(['GET', 'POST'], 'h5/delete', 'ctl_h5@delete')->name('admin.h5.delete');
        Route::match(['GET', 'POST'], 'h5/enable', 'ctl_h5@enable')->name('admin.h5.enable');
        Route::match(['GET', 'POST'], 'h5/disable', 'ctl_h5@disable')->name('admin.h5.disable');
        Route::match(['GET', 'POST'], 'h5/export_list', 'ctl_h5@export_list')->name('admin.h5.export_list');
        Route::match(['GET', 'POST'], 'h5/detail', 'ctl_h5@detail')->name('admin.h5.detail');
        Route::match(['GET', 'POST'], 'report/member_increase_data', 'ctl_report@member_increase_data')->name('admin.report.member_increase_data');
        Route::match(['GET', 'POST'], 'report/member_increase_data_export', 'ctl_report@export_list')->name('admin.report.export_list');
        Route::match(['GET', 'POST'], 'news', 'ctl_news@index')->name('admin.news.index');
        Route::match(['GET', 'POST'], 'news/add', 'ctl_news@add')->name('admin.news.add');
        Route::match(['GET', 'POST'], 'news/edit', 'ctl_news@edit')->name('admin.news.edit');
        Route::match(['GET', 'POST'], 'news/delete', 'ctl_news@delete')->name('admin.news.delete');
        Route::match(['GET', 'POST'], 'news/enable', 'ctl_news@enable')->name('admin.news.enable');
        Route::match(['GET', 'POST'], 'news/disable', 'ctl_news@disable')->name('admin.news.disable');
        Route::match(['GET', 'POST'], 'news/export_list', 'ctl_news@export_list')->name('admin.news.export_list');
        Route::match(['GET', 'POST'], 'news_cat', 'ctl_news_cat@index')->name('admin.news_cat.index');
        Route::match(['GET', 'POST'], 'news_cat/add', 'ctl_news_cat@add')->name('admin.news_cat.add');
        Route::match(['GET', 'POST'], 'news_cat/edit', 'ctl_news_cat@edit')->name('admin.news_cat.edit');
        Route::match(['GET', 'POST'], 'news_cat/delete', 'ctl_news_cat@delete')->name('admin.news_cat.delete');
        Route::match(['GET', 'POST'], 'news_cat/enable', 'ctl_news_cat@enable')->name('admin.news_cat.enable');
        Route::match(['GET', 'POST'], 'news_cat/disable', 'ctl_news_cat@disable')->name('admin.news_cat.disable');
        Route::match(['GET', 'POST'], 'news_cat/export_list', 'ctl_news_cat@export_list')->name('admin.news_cat.export_list');
        Route::match(['GET', 'POST'], 'faq', 'ctl_faq@index')->name('admin.faq.index');
        Route::match(['GET', 'POST'], 'faq/add', 'ctl_faq@add')->name('admin.faq.add');
        Route::match(['GET', 'POST'], 'faq/edit', 'ctl_faq@edit')->name('admin.faq.edit');
        Route::match(['GET', 'POST'], 'faq/delete', 'ctl_faq@delete')->name('admin.faq.delete');
        Route::match(['GET', 'POST'], 'faq/enable', 'ctl_faq@enable')->name('admin.faq.enable');
        Route::match(['GET', 'POST'], 'faq/disable', 'ctl_faq@disable')->name('admin.faq.disable');
        Route::match(['GET', 'POST'], 'faq/export_list', 'ctl_faq@export_list')->name('admin.faq.export_list');
        Route::match(['GET', 'POST'], 'faq_cat', 'ctl_faq_cat@index')->name('admin.faq_cat.index');
        Route::match(['GET', 'POST'], 'faq_cat/add', 'ctl_faq_cat@add')->name('admin.faq_cat.add');
        Route::match(['GET', 'POST'], 'faq_cat/edit', 'ctl_faq_cat@edit')->name('admin.faq_cat.edit');
        Route::match(['GET', 'POST'], 'faq_cat/delete', 'ctl_faq_cat@delete')->name('admin.faq_cat.delete');
        Route::match(['GET', 'POST'], 'faq_cat/enable', 'ctl_faq_cat@enable')->name('admin.faq_cat.enable');
        Route::match(['GET', 'POST'], 'faq_cat/disable', 'ctl_faq_cat@disable')->name('admin.faq_cat.disable');
        Route::match(['GET', 'POST'], 'faq_cat/export_list', 'ctl_faq_cat@export_list')->name('admin.faq_cat.export_list');
        Route::match(['GET', 'POST'], 'link', 'ctl_link@index')->name('admin.link.index');
        Route::match(['GET', 'POST'], 'link/add', 'ctl_link@add')->name('admin.link.add');
        Route::match(['GET', 'POST'], 'link/edit', 'ctl_link@edit')->name('admin.link.edit');
        Route::match(['GET', 'POST'], 'link/delete', 'ctl_link@delete')->name('admin.link.delete');
        Route::match(['GET', 'POST'], 'link/enable', 'ctl_link@enable')->name('admin.link.enable');
        Route::match(['GET', 'POST'], 'link/disable', 'ctl_link@disable')->name('admin.link.disable');
        Route::match(['GET', 'POST'], 'link/export_list', 'ctl_link@export_list')->name('admin.link.export_list');
        Route::match(['GET', 'POST'], 'example', 'ctl_example@index')->name('admin.example.index');
        Route::match(['GET', 'POST'], 'example/detail', 'ctl_example@detail')->name('admin.example.detail');
        Route::match(['GET', 'POST'], 'example/add', 'ctl_example@add')->name('admin.example.add');
        Route::match(['GET', 'POST'], 'example/edit', 'ctl_example@edit')->name('admin.example.edit');
        Route::match(['GET', 'POST'], 'example/delete', 'ctl_example@delete')->name('admin.example.delete');
        Route::match(['GET', 'POST'], 'example/enable', 'ctl_example@enable')->name('admin.example.enable');
        Route::match(['GET', 'POST'], 'example/disable', 'ctl_example@disable')->name('admin.example.disable');
        Route::match(['GET', 'POST'], 'example/export_list', 'ctl_example@export_list')->name('admin.example.export_list');
        Route::get('500', function () {
            abort(500, '抱歉，服务器出了小差，请稍候再试！');
        });
    });
});


