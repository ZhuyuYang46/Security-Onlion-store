<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthDemoController;
use App\Http\Controllers\CommentDemoController;
use App\Http\Controllers\OrderDemoController;

// 默认首页
Route::get('/', function () {
    return view('welcome');
});

// ----------------------------
// 1️⃣ SQL Injection Demo
// ----------------------------

// 漏洞版登录页面
Route::get('/login-vuln', [AuthDemoController::class, 'showVulnLogin']);
Route::post('/login-vuln', [AuthDemoController::class, 'doVulnLogin']);

// 安全版登录页面
Route::get('/login-safe', [AuthDemoController::class, 'showSafeLogin']);
Route::post('/login-safe', [AuthDemoController::class, 'doSafeLogin']);

// ----------------------------
// 2️⃣ XSS Demo
// ----------------------------

// 漏洞版评论页面
Route::get('/comment-vuln', [CommentDemoController::class, 'showVulnComments']);
Route::post('/comment-vuln', [CommentDemoController::class, 'storeVulnComment']);

// 安全版评论页面
Route::get('/comment-safe', [CommentDemoController::class, 'showSafeComments']);
Route::post('/comment-safe', [CommentDemoController::class, 'storeSafeComment']);

// ----------------------------
// 3️⃣ Encryption / Storage Demo
// ----------------------------

// 用户注册（包含密码加密）
Route::get('/register', [OrderDemoController::class, 'showRegister']);
Route::post('/register', [OrderDemoController::class, 'doRegister']);

// 提交订单（包含订单信息加密）
Route::get('/order', [OrderDemoController::class, 'showOrder']);
Route::post('/order', [OrderDemoController::class, 'doOrder']);

// 查看用户订单（行级安全演示）
Route::get('/my-orders', [OrderDemoController::class, 'viewMyOrders']);
Route::get('/order/{id}', [OrderDemoController::class, 'viewOrder']);


Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});
