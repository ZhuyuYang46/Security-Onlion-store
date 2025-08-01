<h2>XSS Defense Demonstration Page</h2>

<form method="POST" action="/comment-safe">
    @csrf
    <textarea name="content" rows="3" cols="40" placeholder="Enter the comment, for example: <script>alert(1)</script>"></textarea><br>
    <button type="submit">Submit Comment</button>
</form>

<h3>Latest Comment:</h3>
<ul>
@foreach ($comments as $comment)
    <li>{{ $comment->content }}</li> {{-- ✅ 安全：自动 HTML 转义 --}}
@endforeach
</ul>
