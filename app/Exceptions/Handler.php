<?php

namespace App\Exceptions;

use App\Http\Controllers\admin\ctl_common;
use App\models\mod_common;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
        if ($this->isHttpException($exception))  //自定义404与500页面
        {
            //api接口异常处理
            if ($exception instanceof TokenInvalidException)
            {
                return mod_common::error('获取token失败', -4001); //token不合法
            }
            else if ($exception instanceof TokenExpiredException)
            {
                return mod_common::error('会话已过期, 请尝试重新登录', -4002);
            }
            else if ($exception instanceof UnauthorizedHttpException || $exception instanceof TokenBlacklistedException)
            {
                return mod_common::error('未登录或登录超时', -4003);
            }
            //无权限异常处理
            else if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) //权限不足，无法访问
            {
                if (! $request->expectsJson()){
                    return msgbox([
                        'icon' => 5,
                        'msg' => '权限不足, 对不起，你没权限执行本操作！',
                        'gourl' => '',
                    ]);
                }
                else{
                    return mod_common::error('无权限', -1);
                }
            }
            //web异常处理
            else if ($exception->getStatusCode() == 404) //您访问的页面不存在
            {
                return page_error(['code' => 404]);
            }
            else if ($exception->getStatusCode() == 500) //网站有一个异常，请稍候再试
            {
                return page_error(['code' => 500]);
            }
        }
        return parent::render($request, $exception);
    }
}
