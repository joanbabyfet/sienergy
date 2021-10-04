<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_common;
use App\models\mod_config;
use Illuminate\Http\Request;

class ctl_config extends Controller
{
    //列表
    public function index(Request $request)
    {
        $page_size = ($request->route()->getActionMethod() == 'export_list') ?
            100 : $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no = !empty($page_no) ? $page_no : 1;
        $name    = $request->input('name') ?? '';
        $group    = $request->input('group') ?? '';

        //獲取數據
        $rows = mod_config::list_data([
            'name'      =>  $name,
            'group'      =>  $group,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if($request->route()->getActionMethod() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'title'     =>'變量說明',
                'name'      =>'變量名',
                'value'     =>'變量值',
                'group'     =>'變量組',
                'sort'      =>'排序',
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

        return view('admin.config_index', [
            'list'  =>  $rows['data'],
            'pages' =>  $pages,
            'groups' => mod_config::$group_options,
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
                return mod_common::error(mod_config::get_err_msg($status), $status);
            }
            $this->cache();
            //寫入日志
            mod_admin_user_oplog::add_log("配置添加 ");

            return mod_common::success([], trans('api.api_add_success'));
        }
        else
        {
            return view('admin.config_add', [
                'groups' => mod_config::$group_options,
            ]);
        }
    }

    //修改
    public function edit(Request $request)
    {
        $name = $request->input('name');
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_config::get_err_msg($status), $status);
            }
            $this->cache();
            //寫入日志
            mod_admin_user_oplog::add_log("配置修改 {$name}");

            return mod_common::success([], trans('api.api_update_success'));
        }
        else
        {
            $row = mod_config::detail(['name' => $name]);

            return view('admin.config_edit', [
                'row'   =>  $row,
                'groups' => mod_config::$group_options,
            ]);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_config::save_data([
            'do'            => $request->route()->getActionMethod(),
            'type'          => $request->input('type'),
            'name'          => $request->input('name'),
            'value'         => $request->input('value'),
            'title'         => $request->input('title'),
            'group'         => $request->input('group'),
            'sort'          => $request->input('sort'),
            'create_user'   => $this->uid,
            'update_user'   => $this->uid,
        ]);
        return $status;
    }

    //刪除
    public function delete(Request $request)
    {
        $id = $request->input('ids', []);
        $status = mod_config::del_data([
            'name'            => $id + [-1],
        ]);
        if($status < 0)
        {
            return mod_common::error(mod_config::get_err_msg($status), $status);
        }
        $this->cache();
        //寫入日志
        mod_admin_user_oplog::add_log("配置刪除 ".implode(",", $id));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //设置配置緩存
    private function cache()
    {
        return mod_config::cache(true); //更新缓存
    }
}
