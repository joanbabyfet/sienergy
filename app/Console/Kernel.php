<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 注册到Kernel，定義應用的 artisan 指令
        \App\Console\Commands\cron_example::class,
        \App\Console\Commands\cron_test_mail::class,
        \App\Console\Commands\cron_generate_member_increase_data::class,
        \App\Console\Commands\cron_backup_db::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //每分钟 withoutOverlapping 上文件锁防止相同脚本未结束时重覆调用
        //runInBackground 会干掉重覆脚本
        //$schedule->command('name:action')->everyMinute()->withoutOverlapping()->runInBackground();
        $schedule->command('mail:test')->everyMinute()->withoutOverlapping();
        $schedule->command('news:collect')->everyMinute()->withoutOverlapping();

        //每天一点
        //$schedule->command('name:action')->dailyAt('01:00')->withoutOverlapping()->runInBackground();
        $schedule->command('db:backup')->dailyAt('01:00')->withoutOverlapping()->runInBackground();

        //每小时
        //$schedule->command('name:action')->hourly()->withoutOverlapping()->runInBackground();

        //每小时 某分
        //$schedule->command('name:action')->hourlyAt(30)->withoutOverlapping()->runInBackground();

        //每天 某时:某分
        //$schedule->command('name:action')->dailyAt('11:00')->withoutOverlapping()->runInBackground();

        //每周-某天 某时:某分 day=1为周一
        //$schedule->command('name:action')->weeklyOn(3, '01:30')->withoutOverlapping()->runInBackground();

        //每月-某天 某时:某分 day=1为一号
        //$schedule->command('name:action')->monthlyOn(1, '01:00')->withoutOverlapping()->runInBackground();

        //每年 某月-某日 某时-某分
        //$schedule->command('name:action')->cron('00 22 25 12 *')->withoutOverlapping()->runInBackground();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
