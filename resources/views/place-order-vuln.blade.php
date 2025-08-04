<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Vulnerable Version</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
        }
        textarea { resize: vertical; height: 100px; }
        button { background: #dc3545; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #c82333; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .warning-note { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .vulnerability-info { background: #f8d7da; padding: 10px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 10px 0; color: #721c24; }
    </style>
</head>
<body>
    <a href="/" class="home-link">← Back to Home</a>
    
    <h1>🚨 Place Order - VULNERABLE Version</h1>
    
    <div class="warning-note">
        <h3>⚠️ Security Vulnerabilities Demonstrated:</h3>
        <ul>
            <li><strong>No CSRF Protection:</strong> Form vulnerable to Cross-Site Request Forgery</li>
            <li><strong>SQL Injection:</strong> Direct SQL queries without parameterization</li>
            <li><strong>No Input Validation:</strong> Accepts malicious input</li>
            <li><strong>Plain Text Storage:</strong> Sensitive data stored unencrypted</li>
            <li><strong>No Access Control:</strong> Missing authorization checks</li>
        </ul>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{!! session('success') !!}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- VULNERABLE: No CSRF token -->
    <form method="POST" action="/order-vuln">
        
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" placeholder="e.g., Laptop, Phone, Book">
            <div class="vulnerability-info">🚨 No input sanitization - XSS vulnerable</div>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="text" id="quantity" name="quantity" value="{{ old('quantity', 1) }}">
            <div class="vulnerability-info">🚨 No type validation - SQL injection possible</div>
        </div>

        <div class="form-group">
            <label for="delivery_address">Delivery Address:</label>
            <textarea id="delivery_address" name="delivery_address" placeholder="Full delivery address including city, postal code">{{ old('delivery_address') }}</textarea>
            <div class="vulnerability-info">🚨 Stored in plain text - no encryption</div>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., +1-234-567-8900">
            <div class="vulnerability-info">🚨 Stored in plain text - PII exposed</div>
        </div>

        <div class="form-group">
            <label for="credit_card_number">Credit Card Number:</label>
            <input type="text" id="credit_card_number" name="credit_card_number" value="{{ old('credit_card_number') }}" placeholder="1234-5678-9012-3456">
            <div class="vulnerability-info">🚨 Credit card data stored unencrypted!</div>
        </div>

        <!-- Hidden field that can be manipulated -->
        <input type="hidden" name="user_id" value="{{ auth()->id() ?? '1' }}">
        <div class="vulnerability-info">🚨 Hidden user_id field can be manipulated</div>

        <button type="submit">🚨 Place VULNERABLE Order</button>
    </form>

    <div style="margin-top: 30px; text-align: center;">
        <p><a href="/my-orders-vuln">View My Orders (Vulnerable)</a> | <a href="/logout">Logout</a></p>
    </div>

    <!-- Debug information exposed -->
    @if(config('app.debug'))
        <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
            <h4>🚨 Debug Information Exposed:</h4>
            <p><strong>Current User ID:</strong> {{ auth()->id() ?? 'Not authenticated' }}</p>
            <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
            <p><strong>Server Info:</strong> {{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</p>
        </div>
    @endif

    <script>
        // Vulnerable JavaScript - No input validation
        document.querySelector('form').addEventListener('submit', function(e) {
            // No validation - accepts any input including malicious scripts
            console.log('Form submitted without validation');
        });
    </script>
</body>
</html>