<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class CommentDemoController extends Controller
{
    // 显示漏洞版评论页面（不进行 XSS 过滤）
    public function showVulnComments()
    {
        $comments = Comment::latest()->get();
        return view('comment-vuln', compact('comments'));
    }

    // 存储漏洞版评论
    public function storeVulnComment(Request $request)
    {
        Comment::create([
            'product_id' => 1, // 默认产品ID
            'user_id' => 1, // 默认用户ID
            'content' => $request->input('content'),
        ]);
        return redirect('/comment-vuln');
    }

    // 显示安全版评论页面（自动 HTML 转义）
    public function showSafeComments()
    {
        $comments = Comment::latest()->get();
        return view('comment-safe', compact('comments'));
    }

    // 存储安全版评论
    public function storeSafeComment(Request $request)
    {
        Comment::create([
            'product_id' => 1,
            'user_id' => 1,
            'content' => $request->input('content'),
        ]);
        return redirect('/comment-safe');
    }
}
