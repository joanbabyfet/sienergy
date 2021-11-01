<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_role;
use App\models\mod_user;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_redis;
use Illuminate\Support\Facades\DB;

class ctl_member_level extends Controller
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
        $name    = $request->input('name') ?? '';

        //獲取數據
        $rows = mod_role::list_data([
            'guard_name'=>  config('global.web.guard'),
            'name'      =>  $name,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['created_at', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'name' =>  '等级名称',
                'id'   =>  '等级',
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

        return view('admin.member_level_index', [
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
            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            for($i=2; $i<=15; $i++)
            {
                $id_options[$i] = $i;
            }

            return view('admin.member_level_add', [
                'id_options'    => $id_options,
            ]);
        }
    }

    //修改
    public function edit(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_role::get_err_msg($status), $status);
            }
            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $id = $request->input('id');
            $row = mod_role::detail(['id' => $id]);

            return view('admin.member_level_edit', [
                'row'           =>  $row,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_role::save_data([
            'do'            => mod_common::get_action(),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'guard_name'    => $request->input('guard_name') ?? config('global.web.guard'),
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_role::detail(['id' => $id]);

        return view('admin.member_level_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_role::del_data([
            'id'            => $id + [-1]
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_role::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("會員等級刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }
}
