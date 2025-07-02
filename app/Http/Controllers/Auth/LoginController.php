<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * 显示登录表单
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * 处理登录请求
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard')->with('success', '登录成功');
        }

        return back()->withErrors([
            'email' => '账号或密码错误',
        ])->withInput();
    }

    /**
     * 处理登出请求
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', '您已成功退出登录');
    }
}
