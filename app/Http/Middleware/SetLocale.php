<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // 获取语言设置
        $locale = session('locale', config('app.locale'));
        
        // 验证语言有效性 - 注意这里要和你的路由映射一致
        if (in_array($locale, ['en', 'zh'])) {
            App::setLocale($locale);
        }
        
        return $next($request);
    }
}