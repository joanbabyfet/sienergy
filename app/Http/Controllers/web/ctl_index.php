<?php

namespace App\Http\Controllers\web;

use Illuminate\Http\Request;
use App\models\mod_news;

class ctl_index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        //最新消息
        $news = mod_news::list_data([
            'status'     => 1,
            'limit'      =>  3,
            'order_by'   => ['create_time', 'desc'],
        ]);

        return view('web.index_index', [
            'news' => $news,
        ]);
    }
}
