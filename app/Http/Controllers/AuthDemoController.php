<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthDemoController extends Controller
{
    // 漏洞版登录页面
    public function showVulnLogin()
    {
        return view('login-vuln');
    }

    // 漏洞版登录逻辑（存在 SQL 注入）
    public function doVulnLogin(Request $request)
{
    $email = $request->input('email');
    $password = $request->input('password');

    // 漏洞版：拼接 email + password（为注入制造机会）
    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $users = DB::select($sql);

    if (count($users) > 0) {
        return view('login-result')->with('message', '✅ Login Success (VULNERABLE)');
    } else {
        return view('login-result')->with('message', '❌ Login Failed');
    }
}



    // 安全版登录页面
    public function showSafeLogin()
{
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return view('login-safe');
}

    // 安全版登录逻辑（ORM + 哈希验证）
    public function doSafeLogin(Request $request)
{
    // 1. 验证输入字段
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    // 2. 查询用户
    $user = \App\Models\User::where('email', $credentials['email'])->first();

    // 3. 同时判断用户存在且密码正确
    if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
        // 登录成功
        return view('login-result')->with('message', '✅ Login Success (SECURE)');
    }

    // 登录失败
    return view('login-result')->with('message', '❌ Login Failed');
}



}
