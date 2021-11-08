<?php

namespace App\Http\Controllers\web;

use App\models\mod_common;
use App\models\mod_country;
use App\models\mod_model;
use App\models\mod_user;
use App\models\mod_user_login;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ctl_member extends Controller
{
    use RegistersUsers, SendsPasswordResetEmails;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        parent::__construct();

        //註冊成功後,再進來該頁會跳轉
        $this->middleware('guest');
    }

    /**
     * 重写登录模板
     */
    public function showRegistrationForm()
    {
        //获取手机国码
        $mobile_prefix_options = mod_country::get_mobile_prefix();

        return view("web.member_showRegistrationForm", [
            'mobile_prefix_options' => $mobile_prefix_options,
        ]);
    }

    /**
     * 保存
     * @param array $data
     * @return mixed
     */
    protected function create(array $data)
    {
         $status = mod_user::save_data([
            'do'           => 'add',
            'id'            => '',
            'origin'        => 2, //0=其他 1=官网 2=APP
            'username'      => $data['username'],
            'password'      => $data['password'],
            'realname'      => $data['realname'],
            'email'         => $data['email'],
            'role_id'       => config('global.gen_mem_role_id'),
            'phone_code'    => $data['phone_code'],
            'phone'         => $data['phone'],
            'reg_ip'        => request()->ip(),
            'language'        => 'zh-tw',
            'create_user'   => '0',
        ]);
        return mod_user::$return_data;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username'      => ['required', 'string'],
            //confirmed規則與password_confirmation字段匹配
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
            'realname'      => ['required', 'string'],
            'email'         => ['required', 'string'],
            'phone_code'    => ['required'],
            'phone'         => ['required'],
        ]);
    }

    /**
     * 指定使用 web guard守衛
     */
    protected function guard()
    {
        $guard = $this->guard;
        return auth($guard);
    }

    /**
     * 重写注册成功后行为
     * @param Request $request
     * @param $user
     */
    protected function registered(Request $request, $user)
    {
        $session_lifetime = config('session.lifetime'); //会话有效时间默认120分钟

        //写入登入时间与ip
        $login_time = time();
        $login_ip = $request->ip();
        $session_id = Session::getId();
        $cli_hash = md5($user->username.'-'.$login_ip);

        mod_user::save_data([
            'do'                => 'edit',
            'id'                => $user->id,
            'session_id'        => $session_id,
            'session_expire'    => strtotime("+{$session_lifetime} minutes", time()),
            'login_time'        => $login_time,
            'login_ip'          => $login_ip,
            'update_user'       => '0',
        ]);
        //登入日志
        mod_user_login::save_data([
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
     * 重写忘记密码模板
     */
    public function showLinkRequestForm()
    {
        return view('web.member_showLinkRequestForm');
    }

    /**
     * 重写忘记密码提交成功响应
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return mod_common::success([], trans('api.api_add_success'));
    }

    /**
     * 重写忘记密码提交失败响应
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return mod_common::error('该邮箱尚未注册', -1);
    }
}
