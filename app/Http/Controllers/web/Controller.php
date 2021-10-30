<?php

namespace App\Http\Controllers\web;

use App\models\mod_req;
use App\models\mod_web_path;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user = []; //用户信息
    protected $uid = ''; //用户id
    protected $lang = 'zh-tw'; //用戶語系
    protected $timezone = ''; //时区
    protected $guard = ''; //当前使用守卫

    public function __construct()
    {
        define('IN_WEB', 1);
        $guard = config('global.web.guard'); //守卫
        $this->guard = $guard;

        $this->middleware(function ($request, $next) use($guard)
        {
            //优先使用登录用户当前语言环境
            if (!empty(auth($guard)->user()) && !empty(auth($guard)->user()->language))
            {
                $this->lang = auth($guard)->user()->language;
            }
            else
            {
                $this->lang = mod_req::get_language();
            }
            app()->setLocale($this->lang); //设置语言
            $this->timezone = mod_req::get_timezone();

            if(auth($guard)->check()) //确认当前用户是否通过认证
            {
                $this->uid = auth($guard)->user()->getAuthIdentifier();
                $this->user = auth($guard)->user()->toArray();
                //当前认证uid常量,在model里也可使用
                if (!defined('AUTH_UID')) define('AUTH_UID', $this->uid);
            }

            //定义视图全局变量
            View::share('curr_user', $this->user);
            View::share('guard', $guard);

            return $next($request);
        });
    }
}
