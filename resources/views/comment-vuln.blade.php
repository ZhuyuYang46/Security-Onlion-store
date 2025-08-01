<h2>XSS Vulnerability Demonstration Page </h2>

<form method="POST" action="/comment-vuln">
    @csrf
    <textarea name="content" rows="3" cols="40" placeholder="Enter the comment, for example: <script>alert(1)</script>"></textarea><br>
    <button type="submit">Submit Comment</button>
</form>

<h3>Latest Comment:</h3>
<ul>
@foreach ($comments as $comment)
    <li>{!! $comment->content !!}</li> {{-- ❌ 漏洞：不做转义 --}}
@endforeach
</ul>
