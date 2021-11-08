<?php

namespace App\Console\Commands;

use App\models\mod_common;
use Illuminate\Console\Command;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class cron_backup_db extends Command
{
    protected $process;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

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

        //檢測目錄是否存在,不存在則創建
        $upload_dir = storage_path('backup');
        mod_common::path_exists($upload_dir);
        $filename = $upload_dir.'/'.sprintf("%s_%s.sql",
            config('database.connections.mysql.database'),
            date('YmdHis')
        );

        $this->process = new Process(sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $filename
        ));
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
        try {
            $this->process->mustRun(); //使用mustRun()会丟異常

            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => mod_common::SUCCESS,
                'msg' => 'The backup has been proceed successfully',
            ]);
        } catch (ProcessFailedException $e) {
            //记录日志
            mod_common::logger(__METHOD__, [
                'status'  => mod_common::ERROR,
                'errcode' => $e->getCode(),
                'errmsg'  => 'The backup process has been failed',
            ]);
        }

        $size = memory_get_usage();
        $unit = array('b','kb','mb','gb','tb','pb');
        $memory = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
        $time = microtime(true) - $time_start;
        $date = date('Y-m-d H:i:s');
        echo "[{$date}] {$this->signature} Done in $time seconds\t $memory\n";
    }
}
