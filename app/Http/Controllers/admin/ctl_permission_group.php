<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_permission_group;
use App\models\mod_redis;

class ctl_permission_group extends Controller
{
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
        $rows = mod_permission_group::list_data([
            'name'      =>  $name,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['id', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'name'             =>'名稱',
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

        return view('admin.permission_group_index', [
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
                return mod_common::error(mod_permission_group::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("權限組添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            return view('admin.permission_group_add', []);
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
                return mod_common::error(mod_permission_group::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("權限組修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_permission_group::detail(['id' => $id]);

            return view('admin.permission_group_edit', [
                'row'   =>  $row,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_permission_group::save_data([
            'do'        => $request->route()->getActionMethod(),
            'id'        => $request->input('id'),
            'name'     => $request->input('name'),
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_permission_group::detail(['id' => $id]);

        return view('admin.permission_group_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_permission_group::del_data([
            'id'            => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_permission_group::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("權限組刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
