<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use App\models\mod_permission_group;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_permission;
use App\models\mod_redis;

class ctl_permission extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size      = $request->input('limit', 10);
        $page_no        = $request->input('page', 1);
        $page_no        = !empty($page_no) ? $page_no : 1;
        $display_name   = $request->input('display_name') ?? '';
        $guard_name     = $request->input('guard_name') ?? '';
        $pg_id          = $request->input('pg_id') ?? '';

        //獲取權限組
        $permission_groups = mod_permission_group::list_data([
            'order_by' => ['created_at', 'asc']
        ]);
        //插入到数组开头,array_unshift 数组键名会重置0,1,2
        array_unshift($permission_groups, ['id' => 0, 'name' => '未分類']);
        $permission_groups = mod_array::one_array($permission_groups, ['id','name']);
        $guards = config('global.guard_names');

        //獲取數據
        $rows = mod_permission::list_data([
            'display_name'  =>  $display_name,
            'guard_name'    =>  config('global.admin.guard'),
            'pg_id'         =>  $pg_id,
            'page'          =>  $page_no,
            'page_size'     =>  $page_size,
            'count'         => 1,
            'order_by'      => ['id', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'name'                  =>'權限名稱',
                'display_name'          =>'顯示名稱',
                'permission_group_name' =>'權限組別',
                'guard_name'            =>'Guard',
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

        return view('admin.permission_index', [
            'list'              =>  $rows['data'],
            'permission_groups' =>  $permission_groups,
            'guards'            =>  $guards,
            'pages'             =>  $pages,
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
                return mod_common::error(mod_permission::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("權限添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            //获取权限组
            $permission_groups = mod_permission_group::list_data([
                'order_by' => ['created_at', 'asc']
            ]);
            $permission_groups = mod_array::one_array($permission_groups, ['id','name']);

            $guards = config('global.guard_names');

            return view('admin.permission_add', [
                'permission_groups'  =>  $permission_groups,
                'guards' => $guards,
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
                return mod_common::error(mod_permission::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("權限修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_permission::detail(['id' => $id]);

            $permission_groups = mod_permission_group::list_data([
                'order_by' => ['created_at', 'asc']
            ]);
            $permission_groups = mod_array::one_array($permission_groups, ['id','name']);

            $guards = config('global.guard_names');

            return view('admin.permission_edit', [
                'row'   =>  $row,
                'permission_groups'  =>  $permission_groups,
                'guards' => $guards,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_permission::save_data([
            'do'            => $request->route()->getActionMethod(),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'display_name'  => $request->input('display_name'),
            'pg_id'         => $request->input('pg_id') ?? 0,
            'guard_name'    => $request->input('guard_name') ?? config('global.admin.guard'),
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_permission::detail(['id' => $id]);

        return view('admin.permission_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_permission::del_data([
            'id'            => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_permission::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("權限刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
