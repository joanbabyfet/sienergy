<?php

namespace App\Http\Controllers\admin;

use App\models\mod_common;
use App\models\mod_admin_user_oplog;
use Illuminate\Http\Request;

class ctl_admin_user_oplog extends Controller
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
        $username    = $request->input('username') ?? '';
        $date1      = $request->input('date1');
        $date2      = $request->input('date2');

        //獲取數據
        $rows = mod_admin_user_oplog::list_data([
            'username'  =>  $username,
            'date1'      =>  $date1,
            'date2'      =>  $date2,
            'page'      =>  (int)$page_no, //mongo会比对類型
            'page_size' =>  (int)$page_size,
            'count'     => 1,
            'order_by'  => ['op_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {
            $titles = [
                '_id'                =>'ID',
                'username'          =>'用户名',
                'op_ip'          =>'操作地址',
                'op_country'     =>'操作国家',
                'op_time_dis'    =>'操作时间',
                'msg'        =>'操作說明',
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

        return view('admin.admin_user_oplog_index', [
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

        $status = mod_admin_user_oplog::del_data([
            '_id'           => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_admin_user_oplog::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("操作日志刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
