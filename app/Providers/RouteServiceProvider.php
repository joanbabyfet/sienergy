<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/'; //通过guest中间件,检测该用户己认证时跳转到哪(默认/home)

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        $this->mapAdminRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web') //默认用web中间件组
             //->prefix(config('global.web.domain'))    //二级目录 /example
             ->domain(config('global.web.domain'))  //子网域 example.local/example
             ->namespace($this->namespace.'\web')
             ->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware('web') //默认用web中间件组
            //->prefix(config('global.admin.domain'))    //二级目录 /admin/example
            ->domain(config('global.admin.domain'))  //子网域 admin.example.local/example
            ->namespace($this->namespace.'\admin')
            ->group(base_path('routes/admin.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::middleware('api') //默认用api中间件组
            //->prefix(config('global.api.domain'))    //二级目录 /api/example
            ->domain(config('global.api.domain'))  //子网域 api.example.local/example
            ->namespace($this->namespace.'\api')
            ->group(base_path('routes/api.php'));
    }
}
