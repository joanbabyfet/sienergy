<?php

namespace App\Http\Controllers\web;

use App\models\mod_h5;
use Illuminate\Http\Request;

class ctl_about extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
//        $id = 'dd3eb7b69e32a4d7500501f41a739f84';
//        $row = mod_h5::detail(['id' => $id]);

        return view('web.about_index', [
            //'row'   =>  $row,
        ]);
    }
}
