<?php

namespace App\Http\Controllers\socket;

use App\models\mod_admin_user;
use App\models\mod_model;
use Illuminate\Http\Request;

class ctl_admin extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 根据token获取用户id
     * @param $token
     * @return string
     */
    public function get_uid_by_token($token)
    {
        $where = [];
        $where[] = ['session_id', '=', $token];
        $user =  mod_model::get_one([
            'table'     => 'admin_users',
            'fields'    => ['id', 'session_expire'],
            'where'     => $where
        ]);
        //用户未登录或者token超时
        if (empty($user) || $user['session_expire'] < time())
        {
            return '';
        }

        return (string)$user['id'];
    }
}
