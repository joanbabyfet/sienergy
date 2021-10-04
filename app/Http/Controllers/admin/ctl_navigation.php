<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_navigation;
use App\models\mod_redis;
use function MongoDB\BSON\toJSON;

class ctl_navigation extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $type       = $request->input('type');
        $guard_name = $request->input('guard_name');
        $name       = $request->input('name');

        $guards = config('global.guard_names');

        //獲取數據
        $rows = mod_navigation::list_data([
            //'type'          =>  $type,
            //'guard_name'    =>  $guard_name,
            'name'          =>  $name,
            'order_by'      => ['sort', 'asc'],
        ]);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {

        }

        return view('admin.navigation_index', [
            'list'      =>  empty($rows) ? '{}' : mod_common::array_to_str($rows),
            'guards'    =>  $guards,
        ]);
    }

    //匯出功能
//    public function export_list(Request $request)
//    {
//        $this->index($request);
//    }

    //添加
    public function add(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_navigation::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("導航菜單添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            $guards = config('global.guard_names');

            return view('admin.navigation_add', [
                'guards' => $guards
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
                return mod_common::error(mod_navigation::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("導航菜單修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_navigation::detail(['id' => $id]);
            $guards = config('global.guard_names');

            return view('admin.navigation_edit', [
                'row'   =>  $row,
                'guards' => $guards
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_navigation::save_data([
            'do'            => $request->route()->getActionMethod(),
            'id'            => $request->input('id'),
            'parent_id'     => $request->input('parent_id'),
            'icon'          => $request->input('icon'),
            'uri'           => $request->input('uri', ''),
            'permission_name'   => $request->input('permission_name', ''),
            'name'              => $request->input('name'),
            'type'              => $request->input('type') ?? 'admin', //admin=主菜单
            'guard_name'        => $request->input('guard_name') ?? config('global.admin.guard'),
            'sort'          => $request->input('sort'),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //詳情
//    public function detail(Request $request)
//    {
//        $id = $request->input('id');
//        $row = mod_navigation::detail(['id' => $id]);
//
//        return view('admin.navigation_detail', [
//            'row'   =>  $row,
//        ]);
//    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_navigation::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_navigation::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("導航菜單刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
