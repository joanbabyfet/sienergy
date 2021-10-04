<?php

namespace App\Http\Middleware;

use App\models\mod_common;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     * CSRF 白名单
     * @var array
     */
    protected $except = [
        //排除於 CSRF 驗證流程
        //'alipay/*',
        //'*',
    ];

    /**
     * 重写返回json响应
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|mixed
     */
    public function handle($request, Closure $next)
    {
        try
        {
            parent::handle($request,$next);
        }
        catch (\Exception $e)
        {
            if($e instanceof TokenMismatchException)
            {
                return mod_common::error('CSRF Error', -4005); //csrf token不合法
            }
        }

        return $next($request);
    }
}
