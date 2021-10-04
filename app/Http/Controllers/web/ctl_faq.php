<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\models\mod_faq;
use App\models\mod_faq_cat;
use App\models\mod_common;

class ctl_faq extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $page_size  = $request->input('limit', 10);
        $page_no    = $request->input('page', 1);
        $page_no    = !empty($page_no) ? $page_no : 1;
        $cat_id    = $request->input('cat_id');

        //獲取分類
        $cats = mod_faq_cat::list_data([
            'status'    =>  1,
            'order_by'  => ['create_time', 'asc']
        ]);

        //獲取數據
        $rows = mod_faq::list_data([
            'page'      =>  $page_no,
            'page_size' =>  $page_size,
            'cat_id'    =>  $cat_id,
            'status'    =>  1,
            'count'     =>  1,
            'order_by'   =>  ['create_time', 'desc']
        ]);
        //分頁顯示
        $pages = mod_common::pages($rows['total'], $page_size);

        return view('web.faq_index', [
            'list'  =>  $rows['data'],
            'cats'  =>  $cats,
            'pages' =>  $pages,
        ]);
    }
}
