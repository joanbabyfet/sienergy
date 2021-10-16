<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user;
use App\models\mod_admin_user_login;
use App\models\mod_common;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ctl_login extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        parent::__construct();

        //除登出外,其馀该類接口都要通過guest跳转中间件
        $this->middleware("guest:{$this->guard}", ['except' => 'logout']);
    }

    //目前没用到,登录后跳转到哪,路由若通过中间件跳转由handle()控制
    protected $redirectTo = '/';

    /**
     * 重写显示后台登录模板
     */
    public function showLoginForm()
    {
        return view("admin.login_showLoginForm_v2");
    }

    /**
     * 指定使用 admin guard守衛
     */
    protected function guard()
    {
        $guard = $this->guard;
        return auth($guard); //上面定义
    }

    /**
     * 重写验证时使用的用户名字段
     */
    public function username()
    {
        return 'username';
    }

    /**
     * 重写用户登出后行为
     * @param Request $request
     */
    protected function loggedOut(Request $request)
    {
        return msgbox([
            'icon' => 6,
            'title' => '注销登录',
            'msg' => '成功退出登录！',
            'gourl' => route('admin.index.index'),
        ]);
    }

    /**
     * 重写用户登入后行为
     * @param Request $request
     * @param $user
     */
    protected function authenticated(Request $request, $user)
    {
        $session_lifetime = config('session.lifetime'); //会话有效时间默认120分钟

        //写入登入时间与ip
        $login_time = time();
        $login_ip = $request->ip();
        $session_id = Session::getId();
        $cli_hash = md5($user->username.'-'.$login_ip);
        mod_admin_user::save_data([
            'do'                => 'edit',
            'id'                => $user->id,
            'session_id'        => $session_id,
            'session_expire'    => strtotime("+{$session_lifetime} minutes", time()),
            'login_time'        => $login_time,
            'login_ip'          => $login_ip,
        ]);
        //登入日志
        mod_admin_user_login::save_data([
            'uid'               => $user->id,
            'username'          => $user->username,
            'agent'             => $request->userAgent(),
            'session_id'        => $session_id,
            'login_time'        => $login_time,
            'login_ip'          => $login_ip,
            'login_country'     => '',
            'status'            => 1, //登入成功
            'cli_hash'          => $cli_hash,
        ]);
    }

    /**
     * 重写获取验证字段
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only([$this->username(), 'password']);
        $credentials['status'] = 1; //已激活
        return $credentials;
    }
}
