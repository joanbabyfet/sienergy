<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_country;
use App\models\mod_user;
use App\models\mod_array;
use App\models\mod_common;
use App\models\mod_model;
use App\models\mod_model_has_permissions;
use App\models\mod_model_has_roles;
use App\models\mod_permission;
use App\models\mod_role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ctl_member extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    //列表
    public function index(Request $request)
    {
        $page_size = (mod_common::get_action() == 'export_list') ?
            100 : $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no = !empty($page_no) ? $page_no : 1;
        $username    = $request->input('username') ?? '';

        //獲取數據
        $rows = mod_user::list_data([
            'username'  =>  $username,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'username'          =>'用戶名',
                'realname'          =>'真實姓名',
                'role_name'         =>'會員等級',
                'email'             =>'郵箱',
                'login_time_dis'    =>'上次登入',
                'status_dis'        =>'狀態',
                'create_time_dis'   =>'添加時間',
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

        return view('admin.member_index', [
            'list'  =>  $rows['data'],
            'pages' =>  $pages,
        ]);
    }

    //匯出功能
    public function export_list(Request $request)
    {
        $this->index($request);
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
                return mod_common::error(mod_model::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("會員修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_user::detail(['id' => $id]);
            //获取會員等級基础数据
            $roles = mod_role::list_data([
                'guard_name' => config('global.web.guard'),
                'order_by' => ['created_at', 'asc'],
            ]);
            $roles = mod_array::one_array($roles, ['id', 'name']);
            //获取手机国码
            $mobile_prefix_options = mod_country::get_mobile_prefix();

            return view('admin.member_edit', [
                'row'   =>  $row,
                'roles' =>  $roles,
                'mobile_prefix_options' => $mobile_prefix_options,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_user::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'username'      => $request->input('username'),
            'password'      => $request->input('password'),
            'realname'      => $request->input('realname'),
            'email'         => $request->input('email', ''),
            'phone_code'    => $request->input('phone_code', ''),
            'phone'         => $request->input('phone', ''),
            'role_id'       => $request->input('role_id') ??
                            config('global.gen_mem_role_id'),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_user::detail(['id' => $id]);

        return view('admin.member_detail', [
            'row'   =>  $row,
        ]);
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_user::change_status([
            'id'        => $id + [-1],
            'status'    => mod_user::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_model::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("會員啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_user::change_status([
            'id'        => $id + [-1],
            'status'    => mod_user::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_model::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("會員禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }

    //設置獨立權限,已抛弃
//    public function purview(Request $request)
//    {
//        if($request->isMethod('POST'))
//        {
//            //添加到用户独立权限表
//            $status = mod_model_has_permissions::save_data([
//                'do'            => 'add',
//                'model_id'      => $request->input('id'),
//                'permission_id' => $request->input('permissions') ?? [],
//                //生成 App\models\mod_user 完整類名
//                'model_type'    => get_class(new mod_user())
//            ]);
//            if($status < 0)
//            {
//                return mod_common::error(mod_role::get_err_msg($status), $status);
//            }
//            return mod_common::success([], trans('api.api_update_success'));
//        }
//        else
//        {
//            $id = $request->input('id');
//            $row = mod_user::detail(['id' => $id]);
//            //获取权限树
//            $permissions = mod_permission::get_tree([
//                'guard'    => config('global.web.guard'),
//                'order_by' => ['created_at', 'asc'],
//                'is_auth'  => 1,
//            ]);
//            //这里获取用户独立权限 返回一维数组,格式[4,5,6]
//            $purviews = mod_user::get_purviews([
//                'id'    => $id,
//                'type'  => 1, //独立权限
//                'field' => 'id',
//            ]);
//            return view('admin.member_purview', [
//                'row'           =>  $row,
//                'permissions'   =>  $permissions,
//                'purviews'      =>  $purviews,
//            ]);
//        }
//    }

    //清除用戶獨立權限,已抛弃
//    public function del_purview(Request $request)
//    {
//        $id = $request->input('id');
//        $status = mod_user::del_purview([
//            'id'        => $id,
//        ]);
//        if($status < 0)
//        {
//            return mod_common::error(mod_model::get_err_msg($status), $status);
//        }
//        return mod_common::success([], trans('api.api_disable_success'));
//    }
}
