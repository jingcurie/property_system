<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    // public function boot(): void
    // {
    //     // 设置语言为 session 中的 locale，如果没有就用默认
    //     App::setLocale(Session::get('locale', config('app.locale')));
    // }
    public function boot()
{
    // 应用启动时强制同步语言设置
    $this->app->bind('request', function ($app) {
        $request = \Illuminate\Http\Request::capture();
        
        if ($locale = $request->cookie('app_locale')) {
            $app->setLocale($locale);
        }
        
        return $request;
    });

    // 所有视图共享当前语言
    view()->composer('*', function ($view) {
        $view->with('__current_locale', app()->getLocale());
    });
}
}
