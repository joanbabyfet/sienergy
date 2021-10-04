<?php

namespace App\Http\Controllers\admin;

use App\models\mod_config;
use App\models\mod_sys_mail;
use App\models\mod_member_increase_data;
use App\models\mod_model_has_roles;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_display;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\models\mod_redis;
use Illuminate\Support\Collection;
use App\models\mod_web_path;
use Illuminate\Support\Facades\DB;
use App\models\mod_model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\models\mod_admin_user;
use App\models\mod_example;

class ctl_test extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        //pr($request->session()->all());
        //mod_collect_news::collect();
        //若标题已存在采集池，则不重覆写入
        //$where = [];
        //$where[] = ['title', '=', '陶瓷圈？新配色？劳力士探险家II 50周年款猜想'];

//        if(mod_collect_news::get_count(['where' => $where])) //检测采集表
//        {
//            echo '1';
//        }

//        if(mod_news_copy::get_count(['where' => $where]))//检测新闻表
//        {
//            echo '1';
//        }

        //解決js跨域問題
//        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
//        //$origin = 'http://192.168.11.140:8080';
//        //自定義允許跨域名單
//        $allow_origin = [
//            '*',
//            //'http://192.168.11.140:8080',
//        ];
//        if (in_array('*', $allow_origin) || in_array($origin, $allow_origin))
//        {
//            header('Access-Control-Allow-Origin: ' . $origin);
//        }

        //pr(mod_web_path::$back['back_url']);
        //pr(mod_web_path::back_url());
        //pr(mod_web_path::href_path($request));
//       $res = mod_goods::update_stock([
//            'id' => '1ce87947e89352a4acefc877de08e263',
//            'count' => 2
//        ]);

        //echo date('Y-m-d H:i:s');
        //echo time();
        //$row = mod_model::get_list_query('select * from sie_collect_web where status = 0');
        //pr($row);

//        $request = new Request([
//            'page_size'   => 10,
//            'page_no' => 1,
//            'title' => 'xxx',
//        ]);
//        $controller = App::make('App\Http\Controllers\admin\ctl_example');
//        return App::call([$controller, 'index'], ['request' => $request]);

        //echo mod_common::password_hash('admin888');
        //$request->session()->flush();

//        $where = [];
//        $where[] = ['role_id', '=', 30];
//        if(mod_model_has_roles::get_count([
//            'where' => $where
//        ]))
//        {
//            pr(22);
//        }

        //mod_member_increase_data::generate_data('1950/01/01');

//        $view_data = [
//            '103' => [
//                'realname'  =>  '1',
//                'username'  =>  '2',
//                'code'      =>  '3',
//            ],
//            '104' => [
//                'realname'  =>  '4',
//                'username'  =>  '5',
//                'code'      =>  '6',
//            ]
//        ];
//        mod_sys_mail::send([
//            'object_type'   => 2,
//            'object_ids'    => '103，104',
//            'subject'       => '帳號登入驗證',
//            'view'          => 'mail.example',
//            'view_data'     => $view_data,
//        ]);

//        mod_sys_mail::send([
//            'object_type'   => 4,
//            'object_ids'    => '2021/09/13,2021/09/15',
//            'subject'       => '帳號登入驗證',
//            'view'          => 'mail.example',
//            'view_data'     => [],
//        ]);

//        DB::enableQueryLog();
//        $rows = mod_model::get_list([
//            'fields'    => [
//                'id', 'username', 'email',
//                mod_model::expr('`realname` As name'),
//            ],
//            'table'     => 'users',
//            'page'      =>  2,
//            'page_size' =>  500,
//        ]);
//        echo '<pre>';
//        print_r(DB::getQueryLog());
//        exit;

//        $uuid = md5(uniqid(mt_rand (), true)); //3577485246144b08e8e675
//        //$uuid = 'df9d5a141d9b97c38b7e6e4455413862';
//        $uid = (hexdec(substr($uuid,0,1)) % 8); //十六进制字符串转换为十进制数
//        echo $uid;

//        $rows = mod_example_mongo::list_data([
//            'page'      =>  1,
//            'page_size' =>  10000,
//            //'order_by'  => ['create_time', 'desc'],
//        ]);
//
//        $insert_data = [];
//        foreach($rows as $row)
//        {
//            $row_plus = [
//                'id'        => mod_common::random('web'),
//                'title'     => $row['title'],
//                'content'   => $row['content'],
//                'status'    => $row['status'],
//            ];
//            $insert_data[] = $row_plus;
//        }
//
//        if(!empty($insert_data))
//        {
//            mod_model::insert_data($insert_data, 'example');
//        }

        //Artisan::call('cron:test_batch_insert', []);
        //pr(mod_redis::zrange('k8', 0, 10));
        //pr(mod_redis::set('kk', 'aaa', 10, false));
        //pr(implode(",", []));
        //pr(mod_common::get_server_file_url('aaa.xls', ''));
        //echo App::environment();
        //pr(mod_redis::info());
        pr(var_dump(mod_config::get('driver_wait_time', [
            'group' => 'config',
            'type' => 'int',
            'default' => 100,
            ])));
    }
}
