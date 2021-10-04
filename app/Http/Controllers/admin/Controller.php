<?php

namespace App\Http\Controllers\admin;

use App\models\mod_navigation;
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
    protected $timezone = ''; //需要转化的时区
    protected $guard = ''; //当前使用守卫

    public function __construct()
    {
        define('IN_ADMIN', 1);
        $guard = config('global.admin.guard'); //后台守卫
        $this->guard = $guard;

        $this->middleware(function ($request, $next) use($guard) //过中间件才会执行
        {
            $this->lang = mod_req::get_language();
            App::setLocale($this->lang); //设置语言
            $this->timezone = mod_req::get_admin_timezone(); //获取需要转化的时区
            $navigation = [];

            if(auth($guard)->check()) //确认当前用户是否通过认证
            {
                $this->uid = auth($guard)->user()->getAuthIdentifier();
                $this->user = auth($guard)->user()->toArray();
                //当前认证uid常量,在model里也可使用
                if (!defined('AUTH_UID')) define('AUTH_UID', $this->uid);

                $purviews = get_purviews([ //获取当前用户权限，返回路由名称
                    'guard' => $guard,
                    'field' => 'name'
                ]);
                $navigation = mod_navigation::get_tree([ //获取菜单
                    'guard'         => $guard,
                    'type'          => 'admin',
                    'purviews'      => $purviews,
                    'is_permission' => 1,
                    'order_by'      => ['sort', 'asc']
                ]);
            }

            //定义视图全局变量
            View::share('back', mod_web_path::start());
            View::share('curr_user', $this->user);
            View::share('guard', $guard);
            View::share('navigation', $navigation);

            return $next($request);
        });
    }
}
