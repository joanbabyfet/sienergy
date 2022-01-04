<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [ //全局中间件,每次请求,每个中间件都会执行
        \Fruitcake\Cors\HandleCors::class, //跨域解决
        \App\Http\Middleware\TrustProxies::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class, //检测是否系统维护中
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [ //中间件组,Route::group(['middleware' => 'web'] 会执行中间件组 web 所对应的中间件
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, // 加密 Cookies
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // 加入 Queued Cookies 到 Response
            \Illuminate\Session\Middleware\StartSession::class, // 開啟 Session
            //\Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // 從 Session 中共享錯誤資訊
            \App\Http\Middleware\VerifyCsrfToken::class, // 驗證 CSRF Token
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:60,1', //允許調用60次,超過會報錯
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\mw_check_sign::class, //第三调用,优先级在路由配置文件之后,检测接口签名中间件
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
*/
    protected $routeMiddleware = [ //路由中间件，定义路由时引用
        'auth' => \App\Http\Middleware\Authenticate::class, //前台,后台认证中间件
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class, //api认证中间件
        //'jwt.refresh' => \Tymon\JWTAuth\Http\Middleware\RefreshToken::class, //暂未使用
        //'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'role'       => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \App\Http\Middleware\mw_permission::class,
        'role' => \App\Http\Middleware\mw_role::class,
        'safe_ips' => \App\Http\Middleware\mw_safe_ips::class,
    ];

    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
