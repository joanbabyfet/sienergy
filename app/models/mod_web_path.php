<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class mod_web_path extends Model
{
    public static $back = [];//back相关信息

    public static function start()
    {
        //$back_url = $request->input('back_url');
        $back_url = '';
        $back_url = htmlspecialchars_decode($back_url);//过滤掉html页面传过来的html特殊字符

        $back = [
            'back_url' => $back_url ? $back_url : 'javascript:history.back(-1)',//返回地址
            'history' => $back_url ? null : -1
        ];

        self::$back = $back;

        return $back;
    }

    //取得返回连接
    public static function back_url($url = '')
    {
        $back_url = self::$back['back_url'] ? self::$back['back_url'] : $url;

        return $back_url;
    }

    //取得当前url
    public static function current_url(Request $request)
    {
        $url = $request->getRequestUri();

        return $url;
    }

    //前往
    public static function go(Request $request, $url)
    {
        $url .= "&back_url=".urlencode(self::current_url($request));

        return $url;
    }

    //http地址中/xxx/yyy 这样的路径
    public static function href_path(Request $request)
    {
        $path = $request->path();
        $path=='/' && $path = '';

        return $path;
    }
}
