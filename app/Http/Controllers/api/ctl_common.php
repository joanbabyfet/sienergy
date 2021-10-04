<?php

namespace App\Http\Controllers\api;

use App\models\mod_common;
use Illuminate\Http\Request;

class ctl_common extends Controller
{
    /**
     * ping
     * 检测用,可查看是否返回信息及时间戳
     * @return \Illuminate\Http\JsonResponse
     */
    public function ping()
    {
        return mod_common::success();
    }

    /**
     * 返回客戶端ip
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ip(Request $request)
    {
        return mod_common::success(['ip' => $request->ip()]);
    }
}
