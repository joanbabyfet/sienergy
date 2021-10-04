<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_news;
use App\models\mod_news_cat;
use App\models\mod_redis;
use Illuminate\Support\Facades\Storage;

class ctl_news extends Controller
{
    //是否使用缓存
    private static $is_use_cache = true;
    private static $detail_cache_key = "news:id:%s";

    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size = ($request->route()->getActionMethod() == 'export_list') ?
            100 : $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no = !empty($page_no) ? $page_no : 1;
        $title       = $request->input('title');
        $cat_id    = $request->input('cat_id');

        //獲取分類
        $cats = mod_news_cat::list_data([
            'order_by'  => ['create_time', 'asc']
        ]);
        $cats = mod_array::one_array($cats, ['id','name']);

        //獲取數據
        $rows = mod_news::list_data([
            'title'      =>  $title,
            'cat_id'    =>  $cat_id,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'title'             =>'標題',
                'create_user_dis'   =>'作者',
            ];

            return mod_common::export_data([
                'page_no'   => $page_no,
                'rows'      => $rows['data'],
                'file'      => $request->input('file', ''),
                'fields'    => $request->input('fields', []), //列表所有字段
                'titles'    => $titles, //輸出字段
                'total_page' => $pages->lastPage(),
            ]);
        }

        return view('admin.news_index', [
            'list'  =>  $rows['data'],
            'cats'  =>  $cats,
            'pages' =>  $pages,
        ]);
    }

    //匯出功能
    public function export_list(Request $request)
    {
        $this->index($request);
    }

    //添加
    public function add(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_news::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("新聞添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            $cats = mod_news_cat::list_data(['order_by'  => ['create_time', 'asc']]);
            $cats = mod_array::one_array($cats, ['id','name']);

            return view('admin.news_add', [
                'cats'  =>  $cats,
                'img_thumb_with'    =>  mod_news::$img_thumb_with,
            ]);
        }
    }

    //修改
    public function edit(Request $request)
    {
        $id = $request->input('id');

        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_news::get_err_msg($status), $status);
            }
            //干掉緩存
            if (!empty($id) && self::$is_use_cache)
            {
                mod_redis::del(sprintf(self::$detail_cache_key, $id));
            }
            //寫入日志
            mod_admin_user_oplog::add_log("新聞修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_news::detail(['id' => $id]);

            $cats = mod_news_cat::list_data(['order_by'  => ['create_time', 'asc']]);
            $cats = mod_array::one_array($cats, ['id','name']);

            return view('admin.news_edit', [
                'row'               =>  $row,
                'cats'              =>  $cats,
                'img_thumb_with'    =>  mod_news::$img_thumb_with,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_news::save_data([
            'do'        => $request->route()->getActionMethod(),
            'id'        => $request->input('id'),
            'cat_id'    => $request->input('cat_id'),
            'title'     => $request->input('title'),
            'content'   => $request->input('content', ''),
            'status'    => $request->input('status', 0),
            'file'      => $request->input('file', []),
            'img'       => $request->input('img', []),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_news::detail(['id' => $id]);

        return view('admin.news_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = mod_news::del_data([
            'id'            => $ids + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_news::get_err_msg($status), $status);
        }
        //干掉緩存
        if(!empty($ids) && self::$is_use_cache)
        {
            foreach($ids as $id)
            {
                mod_redis::del(sprintf(self::$detail_cache_key, $id));
            }
        }
        //寫入日志
        mod_admin_user_oplog::add_log("新聞刪除 ".implode(",", $ids));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $ids     = $request->input('ids', []);
        $status = mod_news::change_status([
            'id'        => $ids + [-1],
            'status'    => mod_news::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_news::get_err_msg($status), $status);
        }
        //干掉緩存
        if(!empty($ids) && self::$is_use_cache)
        {
            foreach($ids as $id)
            {
                mod_redis::del(sprintf(self::$detail_cache_key, $id));
            }
        }
        //寫入日志
        mod_admin_user_oplog::add_log("新聞啟用 ".implode(",", $ids));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = mod_news::change_status([
            'id'        => $ids + [-1],
            'status'    => mod_news::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_news::get_err_msg($status), $status);
        }
        //干掉緩存
        if(!empty($ids) && self::$is_use_cache)
        {
            foreach($ids as $id)
            {
                mod_redis::del(sprintf(self::$detail_cache_key, $id));
            }
        }
        //寫入日志
        mod_admin_user_oplog::add_log("新聞禁用 ".implode(",", $ids));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
