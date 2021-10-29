<?php

namespace App\Http\Controllers\web;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ctl_reset_pwd extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 重寫重設密码模板
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('web.reset_pwd_showResetForm')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * 重寫驗證規則
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
