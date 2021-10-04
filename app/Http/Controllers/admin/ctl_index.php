<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user;
use App\models\mod_common;
use App\models\mod_permission;
use App\models\mod_user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ctl_index extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 主入口
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('admin.index_index', []);
    }
}
