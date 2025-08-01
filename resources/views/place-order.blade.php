<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order - Encrypted Data Storage</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; 
        }
        textarea { resize: vertical; height: 100px; }
        button { background: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background: #218838; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .security-note { background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .encryption-info { background: #fff3cd; padding: 10px; border: 1px solid #ffeaa7; border-radius: 4px; margin: 10px 0; }
    </style>
</head>
<body>
    <a href="/" class="home-link">← Back to Home</a>
    
    <h1>🛒 Place Order - Encrypted Data Storage Demo</h1>
    
    <div class="security-note">
        <h3>🔐 Database Security Features:</h3>
        <ul>
            <li><strong>Field-Level Encryption:</strong> All sensitive data encrypted with AES-256</li>
            <li><strong>Row-Level Security:</strong> Orders isolated by user_id</li>
            <li><strong>Audit Trail:</strong> All order operations are logged</li>
            <li><strong>Access Control:</strong> Users can only view their own orders</li>
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

    <form method="POST" action="/order">
        @csrf
        
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name') }}" placeholder="e.g., Laptop, Phone, Book" required>
        </div>

        <div class="form-group">
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
        </div>

        <div class="form-group">
            <label for="delivery_address">Delivery Address:</label>
            <textarea id="delivery_address" name="delivery_address" placeholder="Full delivery address including city, postal code" required>{{ old('delivery_address') }}</textarea>
            <div class="encryption-info">🔒 This address will be encrypted before storage</div>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="e.g., +1-234-567-8900" required>
            <div class="encryption-info">🔒 Phone number will be encrypted before storage</div>
        </div>

        <div class="form-group">
            <label for="credit_card_number">Credit Card Number:</label>
            <input type="text" id="credit_card_number" name="credit_card_number" value="{{ old('credit_card_number') }}" placeholder="1234-5678-9012-3456" maxlength="19" required>
            <div class="encryption-info">🔒 Credit card data will be encrypted with AES-256 before storage</div>
        </div>

        <button type="submit">🔐 Place Encrypted Order</button>
    </form>

    <div style="margin-top: 30px; text-align: center;">
        <p><a href="/my-orders">View My Orders</a> | <a href="/logout">Logout</a></p>
    </div>

    <script>
        // Format credit card input
        document.getElementById('credit_card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{4})(?=\d)/g, '$1-');
            e.target.value = value;
        });
    </script>
</body>
</html>