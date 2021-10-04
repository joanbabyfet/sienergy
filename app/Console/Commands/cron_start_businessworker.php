<?php

namespace App\Console\Commands;

use GatewayWorker\BusinessWorker;
use Illuminate\Console\Command;
use Workerman\Worker;

class cron_start_businessworker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wk:worker {action} {--d}';

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

        $this->start_businessworker();
        Worker::runAll();
    }

    private function start_businessworker()
    {
        //实例化一个容器
        $worker = new BusinessWorker();
        $worker->name               = 'BusinessWorker';
        $worker->count              = 1;
        $worker->registerAddress    = '127.0.0.1:1236';
        $worker->eventHandler       = \App\Events\evt_workerman::class;
    }
}
