<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use GatewayWorker\Lib\Gateway;

class mod_socket extends Model
{
    const SUCCESS = 0;
    const FAIL    = -1;
    //未登录
    const NO_AUTH = 4001;

    /**
     * 发送数据
     *
     * @param array $params
     * @return int|mixed
     */
    public static function send(array $params)
    {
        //参数过滤
        $data_filter = $params;
        $uid       = $data_filter['uid'] ?? ''; //用户id
        $type      = $data_filter['type'] ?? ''; //用户端
        $action    = $data_filter['action'] ?? ''; //socket指令
        $client_id = $data_filter['client_id'] ?? ''; //客户端id
        $data_filter['data'] = empty($data_filter['data']) ? [] : $data_filter['data'];
        //兼容，数据若不是数组json则转数组
        $_data          = is_array($data_filter['data']) ? $data_filter['data'] : json_decode($data_filter['data'], true);
        $data = [
            'action'    => $action,
            'code'      => $data_filter['code'],
            'msg'       => htmlspecialchars_decode($data_filter['msg'], ENT_QUOTES), //雙引號與單引號都要轉換回來
            'timestamp' => time(),
            'data'      => $_data
        ];

        $status = 1;
        $err_msg = '';
        try
        {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE); //中文字不编码
            Gateway::sendToClient($client_id, $data); //向当前client_id发送数据
        }
        catch (\Exception $e)
        {
            $status = -1000 + $e->getCode();
            $err_msg = $e->getMessage();
        }
        finally
        {
            //记录日志，无是是否补获异常都会执行
            mod_common::logger(__METHOD__, [
                'client_id'     => $client_id,
                'action'        => $action,
                'errcode'       => $status,
                'errmsg'        => $err_msg,
                'timestamp'     => time(),
                'uid'           => $uid,
                'data'          => $data,
            ]);
        }

        return $status;
    }
}
