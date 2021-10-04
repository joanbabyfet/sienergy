<?php

namespace App\Console\Commands;

use App\models\mod_sys_mail;
use Illuminate\Console\Command;
use App\models\mod_common;
use App\Jobs\job_test_mail;

class cron_test_mail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:test_mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '发送邮件';

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
        $time_start = microtime(true);

        //執行腳本,将任务放入异步队列中
        $view_data = [
            '103' => [
                'realname'  =>  '1',
                'username'  =>  '2',
                'code'      =>  '3',
            ],
            '104' => [
                'realname'  =>  '4',
                'username'  =>  '5',
                'code'      =>  '6',
            ]
        ];
        mod_sys_mail::send([
            'object_type'   => 2,
            'object_ids'    => '103,104', //2021/09/13,2021/09/15
            'subject'       => '帳號登入驗證',
            'view'          => 'mail.example',
            'view_data'     => $view_data,
        ]);

        $size = memory_get_usage();
        $unit = array('b','kb','mb','gb','tb','pb');
        $memory = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        $time = microtime(true) - $time_start;
        $date = date('Y-m-d H:i:s');
        echo "[{$date}] {$this->signature} Done in $time seconds\t $memory\n";
    }
}
