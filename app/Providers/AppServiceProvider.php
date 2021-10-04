<?php

namespace App\Providers;

use App\models\mod_web_path;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     * 优先级大于 middleware
     * @return void
     */
    public function boot()
    {
        //全局视图变量可定义在父控制器里,不要定义在这
//        $is_maintenance = config('global.is_maintenance');
//        if($is_maintenance)
//        {
//            Artisan::call('down', ['--message' => '系统维护中...']);
//        }
//        else
//        {
//            Artisan::call('up', []);
//        }
    }
}
