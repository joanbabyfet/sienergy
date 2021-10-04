<?php

namespace App\Http\Controllers\socket;

use App\models\mod_user;
use Illuminate\Http\Request;

class ctl_client extends Controller
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
        $uid = mod_user::get_uid_by_token($token);

        if (empty($uid) || empty($user = mod_user::detail(['id' => $uid])))
        {
            return '';
        }

        $this->user = $user; //获取用户信息

        return (string)$user['id'];
    }
}
