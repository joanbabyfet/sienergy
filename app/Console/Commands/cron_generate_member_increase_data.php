<?php

namespace App\Console\Commands;

use App\Jobs\job_generate_member_increase_data;
use Illuminate\Console\Command;

class cron_generate_member_increase_data extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'member_increase_data:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '腳本描述';

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
        $from_date = '1950/01/01'; //测试用
        //$from_date = date('Y/m/d', strtotime('-1 day'));
        $job = new job_generate_member_increase_data(['from_date' => $from_date]);
        dispatch($job);

        $size = memory_get_usage();
        $unit = array('b','kb','mb','gb','tb','pb');
        $memory = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        $time = microtime(true) - $time_start;
        $date = date('Y-m-d H:i:s');
        echo "[{$date}] {$this->signature} Done in $time seconds\t $memory\n";
    }
}
