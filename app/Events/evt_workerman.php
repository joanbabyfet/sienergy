<?php

namespace App\Events;

use App\models\mod_common;
use GatewayWorker\Lib\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

/**
 * 监听处理 workerman 各种事件
 * 主要处理 onConnect onMessage onClose 三个方法
 * Class evt_workerman
 * @package App\Events
 */
class evt_workerman
{
    //该回调函数Gateway启动时触发
    public static function onWorkerStart($businessWorker)
    {
    }

    //当客户端连接时触发
    public static function onConnect($client_id)
    {
        //记录日志
        mod_common::logger(__METHOD__, [
            'client_id' => $client_id,
            'session'   => $_SESSION
        ]);
    }

    //当客户端连接WebSocket时触发
    public static function onWebSocketConnect($client_id, $data)
    {
    }

    //客户端送来消息时
    public static function onMessage($client_id, $message)
    {
        //心跳包直接返回
        if (!is_array($message) && $message === '~H#C~')
        {
            return Gateway::sendToCurrentClient('~H#S~');
        }

        $start_timestamp = time();
        $data = $message;
        $data = is_array($data) ? $data : json_decode($data, true);
        $ip             = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
        $gateway_port   = $_SERVER['GATEWAY_PORT'];

        $tcp_gateway_map = [
            '2346'      => 'client',
            '2347'      => 'admin'
        ];

        //正常tcp
        $req_data = [];
        if (!empty($tcp_gateway_map[$gateway_port]))
        {
            //必填参数
            foreach (['action', 'token'] as $_f)
            {
                if (empty($data[$_f]))
                {
                    return Gateway::sendToClient($client_id, 'invalid request, received->' . $message);
                }
            }

            $req_data   = [
                'client_id' => $client_id,
                'action'    => empty($data['action']) ? '' : $data['action'],
                'token'     => empty($data['token']) ? '' : $data['token'],
                'data'      => empty($data['data']) ? [] : $data['data']
            ];
            $req_data['ct'] = $tcp_gateway_map[$gateway_port];
        }
        else
        {
            return Gateway::sendToClient($client_id, 'invalid request');
        }

        $ctl = empty($req_data['ct']) ? '' : 'ctl_'.$req_data['ct'];
        $request = new Request($req_data);//发送数据
        $controller = app()->make("App\Http\Controllers\socket\\".$ctl); //從容器解析型別
        //调用请求接口,先到处理入口
        app()->call([$controller, 'handle'], [
            'request' => $request
        ]);

        //记录日志
        mod_common::logger(__METHOD__, [
            'action'        => 'finish',
            'client_id'     => $client_id,
            'client_ip'     => $ip,
            'req_data'      => $req_data,
            'done_seconds'  => time() - $start_timestamp,
            'timestamp'     => time()
        ]);
    }

    //当客户端断开连接时
    public static function onClose($client_id)
    {
        if(empty($_SESSION['uid']))
        {
            //记录日志
            mod_common::logger(__METHOD__, [
                'no_say_hi' => true,
                'client_id' => $client_id,
                'session'   => $_SESSION,
            ]);
        }
        else
        {
            //记录日志
            mod_common::logger(__METHOD__, [
                'client_id' => $client_id,
                'session'   => $_SESSION,
            ]);
        }
    }
}
