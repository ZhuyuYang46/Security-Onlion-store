<!DOCTYPE html>
<html>
<head>
    <title>Login - VULNERABLE</title>
</head>
<body>
    <h2>🛑 Vulnerable Login Page (SQL Injection)</h2>
    <form method="POST" action="/login-vuln">
        @csrf
        <label>Email:</label><br>
        <input type="text" name="email"><br><br>

        <label>Password:</label><br>
        <input type="text" name="password"><br><br>

        <button type="submit">Login</button>
    </form>

    <p>Try this injection:</p>
<ul>
  <li><b>Email:</b> <code>' OR 1=1 --</code></li>
  <li><b>Password:</b> <code>anything</code></li>
</ul>
</body>
</html>
