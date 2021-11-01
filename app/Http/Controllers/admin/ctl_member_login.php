<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_user_login;
use App\models\mod_common;
use Illuminate\Http\Request;

class ctl_member_login extends Controller
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
        $date1      = $request->input('date1');
        $date2      = $request->input('date2');

        //獲取數據
        $rows = mod_user_login::list_data([
            'username'  =>  $username,
            'date1'      =>  $date1,
            'date2'      =>  $date2,
            'page'      =>  (int)$page_no, //mongo会比对類型
            'page_size' =>  (int)$page_size,
            'count'     => 1,
            'order_by'  => ['login_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                '_id'                =>'ID',
                'username'          =>'用户名',
                'login_ip'          =>'登录ip',
                'login_country'     =>'登录国家',
                'login_time_dis'    =>'登录时间',
                'status_dis'        =>'登录时状态',
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

        return view('admin.member_login_index', [
            'list'  =>  $rows['data'],
            'pages' =>  $pages,
        ]);
    }

    //匯出功能
    public function export_list(Request $request)
    {
        $this->index($request);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_user_login::del_data([
            '_id'           => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_user_login::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("會員登入日志刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
