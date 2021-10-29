<?php

namespace App\Http\Controllers\web;

use App\models\mod_common;
use App\models\mod_model;
use App\models\mod_user;
use Illuminate\Http\Request;

class ctl_change_pwd extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 修改密码
     */
    public function edit(Request $request)
    {
        if($request->isMethod('POST'))
        {
            $old_password = $request->input('old_password');
            //检测原密码
            if(!mod_common::check_password($old_password, auth($this->guard)->user()->password))
            {
                return mod_common::error('原密碼不正確', -1);
            }

            $status = mod_user::save_data([
                'do'            => $request->route()->getActionMethod(),
                'id'            => $request->input('id'),
                'password'      => $request->input('password'),
                'update_user'   => $this->uid,
            ]);
            if($status < 0)
            {
                return mod_common::error(mod_model::get_err_msg($status), $status);
            }
            return mod_common::success([], trans('api.api_submit_success'));
        }
        else
        {
            //返回基礎數據
            if($request->ajax())
            {

            }
            $id = $this->uid;
            $row = mod_user::detail(['id' => $id]);

            return view('web.change_pwd_edit', [
                'row'   =>  $row,
            ]);
        }
    }
}
