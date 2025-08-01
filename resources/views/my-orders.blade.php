<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Row-Level Security Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .security-note { background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; }
        .order-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .order-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
        .order-id { font-weight: bold; color: #007bff; }
        .order-date { color: #6c757d; font-size: 0.9em; }
        .encrypted-data { background: #fff; border: 1px solid #ddd; border-radius: 4px; padding: 15px; }
        .data-row { margin: 8px 0; }
        .data-label { font-weight: bold; color: #495057; }
        .sensitive-data { background: #fff3cd; padding: 5px; border-radius: 3px; }
        .no-orders { text-align: center; padding: 40px; color: #6c757d; }
        .btn { display: inline-block; padding: 8px 16px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <a href="/" class="home-link">← Back to Home</a>
    
    <h1>📋 My Orders - Row-Level Security Demo</h1>
    
    <div class="security-note">
        <h3>🛡️ Security Features in Action:</h3>
        <ul>
            <li><strong>Row-Level Security:</strong> You can only see YOUR orders (filtered by user_id)</li>
            <li><strong>Data Decryption:</strong> Encrypted data is decrypted for authorized viewing</li>
            <li><strong>Audit Logging:</strong> This page access has been logged</li>
            <li><strong>Access Control:</strong> Try accessing /order/123 - you'll be blocked if it's not yours</li>
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

    @if(isset($orders) && count($orders) > 0)
        <div style="margin-bottom: 20px;">
            <strong>Total Orders:</strong> {{ count($orders) }}
        </div>

        @foreach($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <span class="order-id">Order #{{ $order['id'] }}</span>
                    <span class="order-date">{{ \Carbon\Carbon::parse($order['created_at'])->format('M j, Y g:i A') }}</span>
                </div>
                
                <div class="encrypted-data">
                    <h4>📦 Decrypted Order Details:</h4>
                    
                    <div class="data-row">
                        <span class="data-label">Product:</span> {{ $order['data']['product_name'] }}
                    </div>
                    
                    <div class="data-row">
                        <span class="data-label">Quantity:</span> {{ $order['data']['quantity'] }}
                    </div>
                    
                    <div class="data-row">
                        <span class="data-label">Delivery Address:</span> 
                        <span class="sensitive-data">🔓 {{ $order['data']['delivery_address'] }}</span>
                    </div>
                    
                    <div class="data-row">
                        <span class="data-label">Phone:</span> 
                        <span class="sensitive-data">🔓 {{ $order['data']['phone_number'] }}</span>
                    </div>
                    
                    <div class="data-row">
                        <span class="data-label">Credit Card:</span> 
                        <span class="sensitive-data">🔓 {{ $order['data']['credit_card_number'] }}</span>
                    </div>
                    
                    <div class="data-row">
                        <span class="data-label">Order Date:</span> {{ $order['data']['order_date'] }}
                    </div>
                </div>
                
                <div style="margin-top: 15px;">
                    <a href="/order/{{ $order['id'] }}" class="btn">View Details</a>
                </div>
            </div>
        @endforeach
    @else
        <div class="no-orders">
            <h3>📭 No Orders Found</h3>
            <p>You haven't placed any orders yet.</p>
            <a href="/order" class="btn">Place Your First Order</a>
        </div>
    @endif

    <div style="margin-top: 30px; text-align: center; border-top: 1px solid #dee2e6; padding-top: 20px;">
        <a href="/order" class="btn">🛒 Place New Order</a>
        <a href="/logout" style="margin-left: 10px; color: #dc3545; text-decoration: none;">Logout</a>
    </div>

    <div style="margin-top: 30px; padding: 15px; background: #f1f3f4; border-radius: 8px; font-size: 0.9em;">
        <strong>🔍 Security Test:</strong> Try manually visiting <code>/order/[ID]</code> where ID is not your order. 
        The system will block access and log the unauthorized attempt.
    </div>
</body>
</html>