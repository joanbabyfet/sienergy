<?php

namespace App\Http\Controllers\admin;

use App\models\mod_admin_user_oplog;
use App\models\mod_common;
use App\models\mod_redis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ctl_cache extends Controller
{
    //获取Redis服务器信息
    public function redis_info(Request $request)
    {
        $keyword    = $request->input('keyword');
        $tmp = [];
        $rows = mod_redis::info();

        if (!empty($keyword))
        {
            foreach ($rows as $k => $v)
            {
                if (strpos($k, $keyword) !== false)
                {
                    $k = str_replace($keyword, "<font color='red'>{$keyword}</font>", $k);
                    $tmp[$k] = $v;
                }
            }
            $rows = $tmp;
        }

        return view('admin.cache_redis_info', [
            'list'  =>  $rows,
        ]);
    }

    //Redis列表
    public function redis_keys(Request $request)
    {
        $keyword    = $request->input('keyword');
        $list = [];
        $keys = mod_redis::keys($keyword); //获取所有键名

        foreach ($keys as $key)
        {
            //干掉前綴
            $key = str_replace(config('database.redis.options.prefix'), '', $key);
            $len  = mod_redis::lLen($key);
            $ttl  = mod_redis::ttl($key);
            $type = mod_redis::_type($key);
            $list[] = [
                'key'  => $key,
                'len'  => $len,
                'ttl'  => $ttl,
                'type' => $type,
            ];
        }
        ksort($list); //按照键名对数组排序

        return view('admin.cache_redis_keys', [
            'list'  =>  $list,
        ]);
    }

    //刪除对应Key的内容
    public function delete(Request $request)
    {
        $keys = $request->input('keys', []);

        foreach ($keys as $key)
        {
            mod_redis::del($key);
        }
        //寫入日志
        mod_admin_user_oplog::add_log("刪除Redis缓存 ".implode(",", $keys));

        return mod_common::success([], trans('api.api_delete_success'));
    }

    //查看
    public function detail(Request $request)
    {
        $key = $request->input('key');
        $type = mod_redis::_type($key);

        if ($type == 'list') // 如果是队列
        {
            $val = mod_redis::rpop($key);
        }
        elseif ($type == 'zset') //有序集合
        {
            $val = mod_redis::zRange($key, -1000000 , 100000000000);
        }
        else
        {
            $val = mod_redis::get($key);
        }

        if (empty($val))
        {
            $val = "The value of {$key} does not exist";
        }
        elseif (!is_array($val))
        {
            $val = mod_redis::decode($val); //json字符串轉數組
        }

        return view('admin.cache_detail', [
            'val'   =>  $val,
        ]);
    }
}
