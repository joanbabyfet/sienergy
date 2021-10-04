<?php

namespace App\Console\Commands;

use GatewayWorker\Gateway;
use Illuminate\Console\Command;
use Workerman\Worker;

class cron_start_ws_client_gateway extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wk:ws_client_gateway {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        global $argv; //当运行于命令行(CLI)下传参给当前脚本
        $action = $this->argument('action'); //获取指令参数
        //$argv[0] = 'wk'; //干掉才能正常啟用
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : ''; //守护进程,workerman可后台一直运行

        $gateway_type = 'ws_client';
        $this->start_gateway($gateway_type);
        Worker::runAll();
    }

    private function start_gateway($gateway_type)
    {
        $config = config('global.socket.hosts');

        //实例化一个容器,客户端定时发送心跳(推荐)
        $gateway = new Gateway($config[$gateway_type]['listen']);
        $gateway->name                  = $config[$gateway_type]['name'];
        $gateway->count                 = config('global.socket.process_count'); //CPU核数的1-3倍
        $gateway->lanIp                 = config('global.socket.lan_ip');
        $gateway->startPort             = $config[$gateway_type]['start_port'];
        $gateway->pingInterval          = config('global.socket.ping_interval'); //幾秒送一次心跳包,0=不送
        $gateway->pingNotResponseLimit  = config('global.socket.ping_not_response_limit'); //送心跳包后,客户端不回应N次后即断开连接
        $gateway->pingData              = config('global.socket.ping_data'); //向客户端送心跳数据
        $gateway->registerAddress       = config('global.socket.register_address');
    }
}
