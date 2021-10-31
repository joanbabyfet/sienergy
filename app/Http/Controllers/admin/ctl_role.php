<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_permission;
use App\models\mod_permission_group;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_role;
use App\models\mod_redis;
use Spatie\Permission\Models\Role;

class ctl_role extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size  = $request->input('page_size', 10);
        $page_no    = $request->input('page_no', 1);
        $page_no = !empty($page_no) ? $page_no : 1;
        $name    = $request->input('name') ?? '';

        //獲取數據
        $rows = mod_role::list_data([
            'name'      =>  $name,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['created_at', 'desc'],
            'guard_name'=> config('global.admin.guard'),
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'name'         =>'用戶組名',
                'guard_name'   =>'Guard',
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

        return view('admin.role_index', [
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
                return mod_common::error(mod_role::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("用戶組添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            //获取权限树
            $permissions = mod_permission::get_tree([
                'guard'    => config('global.admin.guard'),
                'order_by' => ['created_at', 'asc'],
                'is_auth'  => 1,
            ]);
            $guards = config('global.guard_names');

            return view('admin.role_add', [
                'permissions'  =>  $permissions,
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
                return mod_common::error(mod_role::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("用戶組修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_role::detail(['id' => $id]);
            //获取权限树
            $permissions = mod_permission::get_tree([
                'guard'    => $row['guard_name'],
                'order_by' => ['created_at', 'asc'],
                'is_auth'  => 1,
            ]);

            $guards = config('global.guard_names');
            //获取组权限
            $role = Role::findById($id, $row['guard_name']);
            //返回一维数组,格式[4,5,6]
            $purviews = $role->permissions()->pluck('id')->toArray();

            return view('admin.role_edit', [
                'row'           =>  $row,
                'permissions'   =>  $permissions,
                'guards'        => $guards,
                'purviews'      =>  $purviews,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        //表单验证
//        $this->validate($request, [
//            'name' => 'required|max:20',
//        ]);

        $status = mod_role::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'guard_name'    => $request->input('guard_name') ?? config('global.admin.guard'),
            'permissions'   => $request->input('permissions') ?? [],
        ]);
        return $status;
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_role::del_data([
            'id'            => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_role::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("用戶組刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
