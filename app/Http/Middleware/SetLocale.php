<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class SetLocale
{
    public function handle($request, Closure $next)
{
    // 强制立即启动 Session
    if (!session()->isStarted()) {
        session()->start();
    }

    // 三重保险获取语言设置
    $locale = $request->query('lang') 
              ?? session('locale')
              ?? $request->cookie('app_locale')
              ?? config('app.locale');
              
    // 验证语言有效性
    if (in_array($locale, ['en', 'zh', 'zh_CN'])) {
        // 同步设置到所有存储层
        // app()->setLocale($locale);
        // session()->put('locale', $locale);
        // Config::set('app.locale', $locale);
       
        App::setLocale($locale);
        Config::set('app.locale', $locale);
        
        // 立即写入存储
        session()->save();
        
        // 调试日志
        // Log::debug('Locale SET', [
        //     'method' => __METHOD__,
        //     'locale' => $locale,
        //     'session_id' => session()->getId(),
        //     'session_data' => session()->all()
        // ]);
    }

    $response = $next($request);
    
    // 确保响应中包含语言 Cookie
    return $response->cookie('app_locale', $locale, config('session.lifetime'));
}
}
