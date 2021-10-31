<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_array;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_faq;
use App\models\mod_faq_cat;

class ctl_faq extends Controller
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
        $question       = $request->input('question');
        $cat_id    = $request->input('cat_id');

        //獲取分類
        $cats = mod_faq_cat::list_data(['order_by'  => ['create_time', 'asc']]);

        //獲取數據
        $rows = mod_faq::list_data([
            'question'  =>  $question,
            'cat_id'    =>  $cat_id,
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
                'question'             =>'問題',
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

        return view('admin.faq_index', [
            'list'  =>  $rows['data'],
            'cats'  =>  $cats,
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
                return mod_common::error(mod_faq::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("問答添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            $cats = mod_faq_cat::list_data(['order_by'  => ['create_time', 'asc']]);
            $cats = mod_array::one_array($cats, ['id','name']);

            return view('admin.faq_add', [
                'cats'  =>  $cats,
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
                return mod_common::error(mod_faq::get_err_msg($status), $status);
            }
            //寫入日志
            mod_admin_user_oplog::add_log("問答修改 {$id}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_faq::detail(['id' => $id]);

            $cats = mod_faq_cat::list_data(['order_by'  => ['create_time', 'asc']]);
            $cats = mod_array::one_array($cats, ['id','name']);

            return view('admin.faq_edit', [
                'row'   =>  $row,
                'cats'  =>  $cats,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_faq::save_data([
            'do'        => mod_common::get_action(),
            'id'        => $request->input('id'),
            'cat_id'    => $request->input('cat_id'),
            'question'  => $request->input('question', ''),
            'status'    => $request->input('status', 0),
            'answer'    => $request->input('answer', ''),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //詳情
    public function detail(Request $request)
    {
        $id = $request->input('id');
        $row = mod_faq::detail(['id' => $id]);

        return view('admin.faq_detail', [
            'row'   =>  $row,
        ]);
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_faq::del_data([
            'id'            => $id + [-1],
            'delete_user'   => $this->uid
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_faq::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("問答刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //开启
    public function enable(Request $request)
    {
        $id     = $request->input('ids', []);
        $status = mod_faq::change_status([
            'id'        => $id + [-1],
            'status'    => mod_faq::ENABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_faq::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("問答啟用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_enable_success'));
    }

    //禁用
    public function disable(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_faq::change_status([
            'id'        => $id + [-1],
            'status'    => mod_faq::DISABLE,
            'update_user'   => $this->uid,
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_faq::get_err_msg($status), $status);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("問答禁用 ".implode(",", $id));

        return mod_common::success([], trans('api.api_disable_success'));
    }
}
