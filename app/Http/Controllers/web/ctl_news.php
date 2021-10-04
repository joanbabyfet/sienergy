<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_news;
use App\models\mod_news_cat;
use App\models\mod_redis;
use Route;

class ctl_news extends Controller
{
    //是否使用缓存
    private static $is_use_cache = true;
    private static $cache_key = "news_cat";
    private static $detail_cache_key = "news:id:%s";

    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size  = $request->input('page_size', 10);
        $page_no    = $request->input('page_no', 1);
        $page_no    = !empty($page_no) ? $page_no : 1;
        $name       = $request->input('name');
        $cat_id    = $request->input('cat_id');

        //獲取分類
        $cats = [];
        self::$is_use_cache and $cats = mod_redis::get(self::$cache_key);//獲取緩存數據
        if(empty($cats))
        {
            $cats = mod_news_cat::list_data([
                'status'    =>  1,
                'order_by'  => ['create_time', 'asc']
            ]);
            self::$is_use_cache and mod_redis::set(self::$cache_key, $cats); //設置緩存
        }

        //獲取數據
        $rows = mod_news::list_data([
            'name'      =>  $name,
            'cat_id'    =>  $cat_id,
            'status'    =>  1,
            'count'     =>  1,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {

        }

        return view('web.news_index', [
            'list'  =>  $rows['data'],
            'cats'  =>  $cats,
            'pages' =>  $pages,
        ]);
    }

    //詳情
    public function detail(Request $request)
    {
        $id  = $request->input('id');

        $row = [];
        self::$is_use_cache and $row = mod_redis::get(sprintf(self::$detail_cache_key, $id));
        if(empty($row))
        {
            $row = mod_news::detail(['id' => $id]);
            self::$is_use_cache and mod_redis::set(sprintf(self::$detail_cache_key, $id), $row); //設置緩存
        }

        //獲取分類
        $cats = [];
        self::$is_use_cache and $cats = mod_redis::get(self::$cache_key);//獲取緩存數據
        if(empty($cats))
        {
            $cats = mod_news_cat::list_data([
                'status'    =>  1,
                'order_by'  => ['create_time', 'asc']
            ]);
            self::$is_use_cache and mod_redis::set(self::$cache_key, $cats); //設置緩存
        }

        return view('web.news_detail', [
            'news'  =>  $row,
            'cats'  =>  $cats,
        ]);
    }
}
