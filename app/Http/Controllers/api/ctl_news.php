<?php

namespace App\Http\Controllers\api;

use App\models\mod_common;
use App\models\mod_news;
use Illuminate\Http\Request;

class ctl_news extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $page_size = $request->input('page_size', 10);
        $page_no = $request->input('page_no', 1);
        $page_no = !empty($page_no) ? $page_no : 1;

        $cat_id = $request->input('cat_id');
        $title    = $request->input('title') ?? '';

        //獲取數據
        $rows = mod_news::list_data([
            'cat_id'    => $cat_id,
            'title'     =>  $title,
            'page'      => $page_no,
            'page_size' => $page_size,
            'count'     => 1,
            'order_by'  => ['create_time', 'desc'],
        ]);

        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        if (mod_common::get_action() == 'export_list') //獲取調用方法名
        {

        }

        return mod_common::success([
            'data' => $rows['data'],
            'total_page' => $pages->lastPage(),
            'total' => $pages->total()
        ]);
    }
}
