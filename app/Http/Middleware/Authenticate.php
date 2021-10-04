<?php

namespace App\Http\Middleware;

use App\models\mod_common;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected $guards; //守卫

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        //检测到该用户未登录时重定向
        if (! $request->expectsJson())
        {
            if (!$this->guards || in_array('web', $this->guards)) {
                //路由若没指定守卫，则统一跳转到前台登入页
                return route('web.login.showLoginForm');
            }
            else if (in_array('admin', $this->guards)) {
                return route("admin.login.showLoginForm");
            }
//            else if (in_array('api', $this->guards)) {
//                return mod_common::error(trans('api.api_not_login'));
//            }
        }
        else
        {
            return mod_common::error(trans('api.api_not_login'));
        }
    }

    /**
     * 这里第二调用，验证并获取守卫名称
     * @param \Illuminate\Http\Request $request
     * @param array $guards
     * @throws \Illuminate\Auth\AuthenticationException
     */
    protected function authenticate($request, array $guards)
    {
        $this->guards = $guards; //检测路由是否有指定守卫
        parent::authenticate($request, $guards);
    }
}
