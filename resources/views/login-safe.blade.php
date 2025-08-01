<!DOCTYPE html>
<html>
<head>
    <title>Login - SECURE</title>
</head>
<body>
    <h2>✅ Secure Login Page (Safe against SQL Injection)</h2>
    <form method="POST" action="/login-safe">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email"><br><br>

        <label>Password:</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit">Login</button>
    </form>

    @if ($errors->any())
    <div style="color:red;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

</body>
</html>
