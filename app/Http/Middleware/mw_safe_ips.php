<?php

namespace App\Http\Middleware;

use App\models\mod_common;
use Closure;

class mw_safe_ips
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //获取用户ip白名单
        $safe_ips = auth($guard)->user()->safe_ips;
        $arr_safe_ips = empty($safe_ips) ? [] : explode(',', $safe_ips);

        //登陆IP不在白名单，禁止操作
        if(!empty($safe_ips) && !in_array($request->ip(), $arr_safe_ips))
        {
            $msg = 'IP不在白名单内,无法操作';
            if (! $request->expectsJson())
            {
                return msgbox([
                    'icon' => 5,
                    'title' => '用户权限限制',
                    'msg' => $msg,
                    'gourl' => ''
                ]);
            }
            else
            {
                return mod_common::error($msg);
            }
        }
        return $next($request);
    }
}
