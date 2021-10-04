<?php

namespace App\Http\Controllers\api;

use App\models\mod_common;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ctl_user extends Controller
{
    /**
     * 获取用户信息
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_userinfo()
    {
        $guard = $this->guard;
        $user_info = auth($guard)->user()->toArray();
        return mod_common::success($user_info);
    }
}
