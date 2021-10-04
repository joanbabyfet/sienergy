<?php

namespace App\Http\Controllers\web;

use App\models\mod_common;
use Illuminate\Http\Request;
use App\models\mod_feedback;

class ctl_contact extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        return view('web.contact_index', []);
    }

    public function feedback(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $status = $this->save($request);
            if($status < 0)
            {
                return mod_common::error(mod_feedback::get_err_msg($status), $status);
            }
            return mod_common::success([], trans('api.api_submit_success'));
        }
        else
        {
            //返回基礎數據
            if($request->ajax())
            {

            }
            return view('web.contact_feedback', []);
        }
    }

    /**
     * 保存
     */
    private function save(Request $request)
    {
        $status = mod_feedback::save_data([
            'do'            => 'add',
            'captcha'       => $request->input('captcha'),
            'id'            => $request->input('id'),
            'name'          => $request->input('name'),
            'company_name'  => $request->input('company_name'),
            'sex'           => $request->input('sex'),
            'email'         => $request->input('email'),
            'phone'         => $request->input('phone'),
            'content'       => $request->input('content'),
        ]);

        return $status;
    }
}
