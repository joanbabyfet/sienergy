<?php

namespace App\Http\Controllers\admin;

use App\models\mod_user;
use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_member_increase_data;

class ctl_report extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 會員增長數
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function member_increase_data(Request $request)
    {
        $page_size = (mod_common::get_action() == 'export_list') ?
            100 : $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no = !empty($page_no) ? $page_no : 1;
        $date1      = $request->input('date1');
        $date2      = $request->input('date2');
        $origin     = $request->input('origin');

        //獲取數據
        $rows = mod_member_increase_data::list_data([
            'date1'      =>  $date1,
            'date2'      =>  $date2,
            'origin'      =>  $origin,
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'count'     => 1,
            'order_by'  => ['date', 'desc'],
            'group_by'  => ['date', 'origin'],
            //分组时要加去重,返回总条数才会正确
            'field'     => mod_member_increase_data::expr('DISTINCT date, origin'),
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if(mod_common::get_action() == 'export_list') //獲取調用方法名
        {
            $titles = [
                'date'                  =>'日期',
                'origin_dis'            =>'来源',
                'member_count'          =>'用户數',
                'member_increase_count' =>'用户日增长',
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

        return view('admin.report_member_increase_data', [
            'list'          =>  $rows['data'],
            'pages'         =>  $pages,
            'origins' => mod_user::$origin_map //获取来源选项
        ]);
    }

    //匯出功能
    public function export_list(Request $request)
    {
        $this->member_increase_data($request);
    }
}
