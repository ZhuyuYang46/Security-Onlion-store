<!DOCTYPE html>
<html>
<head>
    <title>Secure Login - Database Security Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; }
        button:hover { background: #0056b3; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .test-accounts { background: #fff3cd; padding: 15px; border-radius: 4px; margin: 20px 0; border: 1px solid #ffeaa7; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <a href="/" class="home-link">← Back to Home</a>
    
    <h2>🔐 Secure Login</h2>
    <p>This login form uses secure authentication practices including:</p>
    <ul>
        <li>✅ Laravel's Auth::attempt() (prevents SQL injection)</li>
        <li>✅ Password hash verification with bcrypt</li>
        <li>✅ Session regeneration (prevents session fixation)</li>
        <li>✅ Input validation and CSRF protection</li>
    </ul>

    <div class="test-accounts">
        <h4>🧪 Test Accounts:</h4>
        <ul>
            <li><strong>alice@example.com</strong> / password123 (3 orders)</li>
            <li><strong>bob@example.com</strong> / password123 (2 orders)</li>
            <li><strong>dave@example.com</strong> / password123 (0 orders)</li>
        </ul>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="/login-safe">
        @csrf
        
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit">🔐 Login Securely</button>
    </form>

    <div style="margin-top: 20px; text-align: center;">
        <p>Don't have an account? <a href="/register">Register here</a></p>
        <p><a href="/login-vuln">🔓 Compare with Vulnerable Login</a></p>
    </div>
</body>
</html>
