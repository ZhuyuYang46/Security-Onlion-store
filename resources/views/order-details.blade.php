<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Security Demo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .order-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 30px; }
        .order-header { text-align: center; margin-bottom: 30px; }
        .order-id { font-size: 1.5em; font-weight: bold; color: #007bff; }
        .order-date { color: #6c757d; margin-top: 5px; }
        .details-section { margin: 20px 0; }
        .section-title { font-weight: bold; color: #495057; margin-bottom: 15px; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        .detail-row { margin: 12px 0; padding: 10px; background: white; border-radius: 4px; }
        .detail-label { font-weight: bold; color: #495057; }
        .detail-value { margin-left: 10px; }
        .sensitive-data { background: #fff3cd; padding: 8px; border-radius: 4px; border-left: 4px solid #ffc107; }
        .encryption-note { background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 20px 0; font-size: 0.9em; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
    </style>
</head>
<body>
    <a href="/my-orders" class="home-link">← Back to My Orders</a>
    
    <h1>📋 Order Details</h1>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($order) && isset($data))
        <div class="order-card">
            <div class="order-header">
                <div class="order-id">Order #{{ $order->id }}</div>
                <div class="order-date">Placed on {{ \Carbon\Carbon::parse($order->created_at)->format('F j, Y \a\t g:i A') }}</div>
            </div>

            <div class="encryption-note">
                <strong>🔐 Security Note:</strong> This data was stored encrypted in the database and has been decrypted for your authorized viewing. 
                Row-level security ensures you can only access your own orders.
            </div>

            <div class="details-section">
                <div class="section-title">📦 Product Information</div>
                
                <div class="detail-row">
                    <span class="detail-label">Product Name:</span>
                    <span class="detail-value">{{ $data['product_name'] }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Quantity:</span>
                    <span class="detail-value">{{ $data['quantity'] }}</span>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">🚚 Delivery Information</div>
                
                <div class="detail-row sensitive-data">
                    <span class="detail-label">🔓 Delivery Address:</span>
                    <div class="detail-value">{{ $data['delivery_address'] }}</div>
                </div>
                
                <div class="detail-row sensitive-data">
                    <span class="detail-label">🔓 Phone Number:</span>
                    <span class="detail-value">{{ $data['phone_number'] }}</span>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">💳 Payment Information</div>
                
                <div class="detail-row sensitive-data">
                    <span class="detail-label">🔓 Credit Card:</span>
                    <span class="detail-value">{{ $data['credit_card_number'] }}</span>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">📅 Order Timeline</div>
                
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">{{ $data['order_date'] }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Database Record Created:</span>
                    <span class="detail-value">{{ $order->created_at }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Last Updated:</span>
                    <span class="detail-value">{{ $order->updated_at }}</span>
                </div>
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/my-orders" class="btn">← Back to All Orders</a>
            <a href="/order" class="btn btn-secondary">Place New Order</a>
        </div>

        <div style="margin-top: 30px; padding: 15px; background: #f1f3f4; border-radius: 8px; font-size: 0.9em;">
            <strong>🔍 Security Features Demonstrated:</strong>
            <ul>
                <li>Data was encrypted with AES-256 before database storage</li>
                <li>Row-level security prevents access to other users' orders</li>
                <li>This page access has been logged in the audit trail</li>
                <li>All sensitive data fields are properly marked and secured</li>
            </ul>
        </div>
    @endif
</body>
</html>