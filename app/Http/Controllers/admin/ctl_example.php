<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_example;
use App\models\mod_redis;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Route;
use App\models\mod_admin_user;
use Illuminate\Support\Facades\Auth;

class ctl_example extends Controller
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
        $title    = $request->input('title') ?? '';

        //獲取數據
        $rows = mod_example::list_data([
            'title'      =>  $title,
            'page'      => $page_no,
            'page_size' => $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);
        //分頁顯示, 是否有下一页 $pages->hasMorePages()
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'title'             =>'標題',
                'sort'              =>'排序',
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

        return view('admin.example_index', [
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
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("文章添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            return view('admin.example_add', [
                'img_thumb_with'    =>  mod_example::$img_thumb_with,
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
                return mod_common::error(mod_example::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("文章修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_example::detail(['id' => $id]);

            return view('admin.example_edit', [
                'row'   =>  $row,
                'img_thumb_with'    =>  mod_example::$img_thumb_with,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_example::save_data([
            'do'        => mod_common::get_action(),
            'id'        => $request->input('id'),
            'title'     => $request->input('title'),
            'content'    => $request->input('content', ''),
            'status'    => $request->input('status', 0),
            'file'      => $request->input('file', []),
            'img'       => $request->input('img', []),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);

        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_example::detail(['id' => $id]);

        return view('admin.example_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);

        $status = mod_example::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_example::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("文章刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_example::change_status([
            'id'        => $id + [-1],
            'status'    => mod_example::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_example::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("文章啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_example::change_status([
            'id'        => $id + [-1],
            'status'    => mod_example::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_example::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("文章禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
