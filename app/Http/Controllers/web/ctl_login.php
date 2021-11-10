<?php

namespace App\Http\Controllers\web;

use App\Jobs\job_send_mail;
use App\models\mod_display;
use App\models\mod_user;
use App\models\mod_user_login;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class ctl_login extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        parent::__construct();

        //除登出外,其馀该類接口都要通過guest跳转中间件
        $this->middleware('guest', ['except' => 'logout']);
    }

    //登录后跳转到哪,路由若通过中间件跳转由handle()控制
    protected $redirectTo = '/';

    /**
     * 重写显示后台登录模板
     */
    public function showLoginForm()
    {
        return view("web.login_showLoginForm");
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
     * 重写验证时使用的用户名字段
     */
    public function username()
    {
        return 'username';
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
        mod_user::save_data([
            'do'                => 'edit',
            'id'                => $user->id,
            'session_id'        => $session_id,
            'session_expire'    => strtotime("+{$session_lifetime} minutes", time()),
            'login_time'        => $login_time,
            'login_ip'          => $login_ip,
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
     * 重写获取验证字段
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $credentials = $request->only([$this->username(), "password"]);
        $credentials['status'] = 1; //已激活
        return $credentials;
    }

    /**
     * 重寫驗證規則
     * @param Request $request
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'captcha' => 'required|captcha'
        ], [
            'captcha.required' => '驗證碼不能為空',
            'captcha.captcha' => '驗證碼不正確',
        ]);
    }

    //Facebook登入
    public function facebookSignInProcess()
    {
        $redirect_url = config('services.facebook.redirect');
        return Socialite::driver('facebook')
            ->scopes(['user_friends'])
            ->redirectUrl($redirect_url)
            ->redirect();
    }

    //Facebook登入重定向授權資料處理
    public function facebookSignInCallbackProcess(Request $request)
    {
        if(request()->error=="access_denied")
        {
            throw new \Exception('授權失敗，存取錯誤');
        }

        //检测是否為發出時同一callback
        $redirect_url = config('services.facebook.redirect');
        //获取第三方用户信息
        $FacebookUser = Socialite::driver('facebook')
            ->fields([ //需要字段
                'name',
                'email',
            ])
            ->redirectUrl($redirect_url)->user();

        $facebook_email = $FacebookUser->email;

        if(is_null($facebook_email))
        {
            throw new \Exception('未授權取得使用者 Email');
        }
        //获取Facebook用户信息, 可用來登入或註冊會員
        $facebook_id = $FacebookUser->id;
        $facebook_name = $FacebookUser->name;

//        echo sprintf("facebook_id=%s, facebook_name=%s, facebook_email=%s",
//            $facebook_id, $facebook_name, $facebook_email);
        //检测是否有用户绑定该facebook_id
        $user = mod_user::detail(['facebook_id' => $facebook_id]);
        if(empty($user))
        {
            //检测是否有用户使用该脸书邮箱(登入帐号)
            $user = mod_user::detail(['email' => $facebook_email]);
            if(!empty($user))
            {
                //有该帳號则綁定Facebook Id
                mod_user::save_data([
                    'do'                => 'edit',
                    'id'                => $user['id'],
                    'facebook_id'       => $facebook_id,
                ]);
            }
            else {
                //添加會員
                $status = mod_user::save_data([
                    'do'           => 'add',
                    'origin'        => 1, //0=其他 1=官网 2=APP
                    'username'      => 'fb_'.$facebook_id,
                    'password'      => 'Bb123456',
                    'realname'      => $facebook_name,
                    'email'         => $facebook_email,
                    'role_id'       => config('global.gen_mem_role_id'),
                    'reg_ip'        => request()->ip(),
                    'language'      => 'zh-tw',
                    'facebook_id'   => $facebook_id,
                    'create_user'   => '0',
                ]);
                $user = mod_user::$return_data;

                if($status > 0)
                {
                    //发送邮件
                    $mail_data = [
                        'realname'      =>  $facebook_name,
                        'username'      =>  'fb_'.$facebook_id,
                        'password'      =>  'Bb123456',
                        'email'         =>  $facebook_email,
                        'create_time'   =>  mod_display::datetime(time()),
                        'reg_ip'        =>  request()->ip(),
                    ];
                    $to = [
                        'email' => $facebook_email,
                        'name'  => $facebook_name
                    ];
                    //發送郵件走異步任務,減少用戶等待時間
                    $job = new job_send_mail([
                        'to'        => $to,
                        'subject'   => '注册成功',
                        'view'      => 'mail.register',
                        'view_data' => $mail_data,
                    ]);
                    dispatch($job);
                }
            }
        }

        is_array($user) and $user = mod_user::find($user['id']);
        $this->guard()->login($user);
        //重新導向到原先使用者造訪頁面，沒有嘗試造訪頁則重新導向回首頁
        return redirect()->intended('/');
    }
}
