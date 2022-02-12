<?php

namespace App\Http\Controllers\api;

use App\models\mod_common;
use App\models\mod_display;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ctl_login extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(["username", "password"]);
        $credentials['status'] = 1; //已激活
        $guard = $this->guard;

        if (!$token = auth($guard)->attempt($credentials))
        {
            return mod_common::error(trans('api.api_login_pass_incorrect'));
        }

        //根据token获取用户信息,jwt后台不需要保存Token
        $user_info = auth($guard)->authenticate($token)->toArray();
        $user_info['api_token'] = $token;
        $jwt_ttl = auth($guard)->factory()->getTTL(); //單位:分鐘
        $api_token_expire = strtotime("+{$jwt_ttl} minutes", time());
        $user_info['api_token_expire'] = $api_token_expire;
        $user_info['api_token_expire_dis'] = mod_display::datetime($api_token_expire);

        return mod_common::success($user_info, trans('api.api_login_success'));
    }

    /**
     * 登出
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $guard = $this->guard;
        auth($guard)->logout();
        return mod_common::success([], trans('api.api_logout_success'));
    }

    /**
     * 登刷新认证token
     * 例如 token 有效时间为 60 分钟，刷新时间为 20160 分钟，在 60 分钟内可以通过这个 token 获取新 token，
     * 但是超过 60 分钟是不可以的，然后你可以一直循环获取，直到总时间超过 20160 分钟，不能再获取
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh_token()
    {
        $guard = $this->guard;
        $token = auth($guard)->refresh();

        //根据token获取用户信息,jwt后台不需要保存Token
        $user_info = auth($guard)->authenticate($token)->toArray();
        $jwt_ttl = auth($guard)->factory()->getTTL(); //單位:分鐘

        $api_token_expire = strtotime("+{$jwt_ttl} minutes", time());

        return mod_common::success([
            'uid'               =>  $user_info['id'],
            'api_token'         =>  $token,
            'api_token_expire'  =>  $api_token_expire,
            'api_token_expire_dis'  => mod_display::datetime($api_token_expire),
        ]);
    }
}
