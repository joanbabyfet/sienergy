<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\models\mod_common;
use App\models\mod_news_cat;
use App\models\mod_redis;

class ctl_news_cat extends Controller
{
    //是否使用缓存
    private static $is_use_cache = false;
    private static $cache_key = "news_cat";

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
        $name    = $request->input('name') ?? '';

        //獲取數據
        $rows = mod_news_cat::list_data([
            'name'      =>  $name,
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
                'name'             =>'分類名稱',
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

        return view('admin.news_cat_index', [
            'list'  =>  $rows['data'],
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
                return mod_common::error(mod_news_cat::get_err_msg($status), $status);
            }
            //干掉緩存
            if (self::$is_use_cache)
            {
                mod_redis::del(self::$cache_key);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("新聞分類添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            return view('admin.news_cat_add', []);
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
                return mod_common::error(mod_news_cat::get_err_msg($status), $status);
            }
            //干掉緩存
            if (!empty($id) && self::$is_use_cache)
            {
                mod_redis::del(self::$cache_key);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("新聞分類修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_news_cat::detail(['id' => $id]);

            return view('admin.news_cat_edit', [
                'row'   =>  $row,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_news_cat::save_data([
            'do'            => $request->route()->getActionMethod(),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'status'        => $request->input('status', 0),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_news_cat::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_news_cat::get_err_msg($status), $status);
        }
        //干掉緩存
        if (!empty($id) && self::$is_use_cache)
        {
            mod_redis::del(self::$cache_key);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("新聞分類刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_news_cat::change_status([
            'id'        => $id + [-1],
            'status'    => mod_news_cat::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_news_cat::get_err_msg($status), $status);
        }
        //干掉緩存
        if (!empty($id) && self::$is_use_cache)
        {
            mod_redis::del(self::$cache_key);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("新聞分類啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_news_cat::change_status([
            'id'        => $id + [-1],
            'status'    => mod_news_cat::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_news_cat::get_err_msg($status), $status);
        }
        //干掉緩存
        if (!empty($id) && self::$is_use_cache)
        {
            mod_redis::del(self::$cache_key);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("新聞分類禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
