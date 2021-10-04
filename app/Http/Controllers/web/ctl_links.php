<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\models\mod_common;
use App\models\mod_link;

class ctl_links extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $page_size  = $request->input('page_size', 10);
        $page_no    = $request->input('page_no', 1);
        $page_no    = !empty($page_no) ? $page_no : 1;

        //獲取數據
        $rows = mod_link::list_data([
            //'page'      =>  $page_no,
            //'page_size' =>  $page_size,
            'status'    =>  1,
            'count'     =>  1,
            'order_by'   =>  ['create_time', 'desc']
        ]);

        return view('web.links_index', [
            'list'  =>  $rows['data'],
        ]);
    }
}
