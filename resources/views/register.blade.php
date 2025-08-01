<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Registration - Security Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"] { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
        }
        button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #0056b3; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .security-note { background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <a href="/" class="home-link">← Back to Home</a>
    
    <h1>🔒 Secure User Registration</h1>
    
    <div class="security-note">
        <h3>🛡️ Security Features Implemented:</h3>
        <ul>
            <li><strong>Password Encryption:</strong> Bcrypt with salt (Laravel Hash::make)</li>
            <li><strong>Input Validation:</strong> Server-side validation with Laravel rules</li>
            <li><strong>Audit Trail:</strong> All registration attempts are logged</li>
            <li><strong>CSRF Protection:</strong> Laravel's built-in CSRF token protection</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/register">
        @csrf
        
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div class="form-group">
            <label for="password">Password (minimum 8 characters):</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit">🔐 Register Securely</button>
    </form>

    <div style="margin-top: 30px; text-align: center;">
        <p>Already have an account? <a href="/login-safe">Login Here</a></p>
    </div>
</body>
</html>