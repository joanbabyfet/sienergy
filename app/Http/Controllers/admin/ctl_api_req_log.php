<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_api_req_log;
use App\models\mod_common;
use Illuminate\Http\Request;

class ctl_api_req_log extends Controller
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
        $date1      = $request->input('date1');
        $date2      = $request->input('date2');
        $req_data   = $request->input('req_data');
        $res_data   = $request->input('res_data');
        $type       = $request->input('type');

        //獲取請求類型
        $types = mod_api_req_log::$type_map;

        //獲取數據
        $rows = mod_api_req_log::list_data([
            'req_data'      =>  $req_data,
            'res_data'      =>  $res_data,
            'type'      =>  $type,
            'date1'      =>  $date1,
            'date2'      =>  $date2,
            'page'      =>  (int)$page_no, //mongo会比对類型
            'page_size' =>  (int)$page_size,
            'count'     => 1,
            'order_by'  => ['req_time', 'desc'],
        ]);

        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {
            $titles = [
                '_id'          =>'ID',
                'type_dis   '  =>'類型',
                'req_data'     =>'請求數據',
                'res_data'     =>'嚮應數據',
                'req_ip'       =>'請求ip',
                'req_time_dis' =>'請求时间',
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

        return view('admin.api_req_log_index', [
            'list'  =>  $rows['data'],
            'pages' =>  $pages,
            'types' =>  $types,
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

        $status = mod_api_req_log::del_data([
            '_id'           => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_api_req_log::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("訪問日志刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
