<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Vulnerable Version</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 900px; margin: 50px auto; padding: 20px; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .warning-note { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .orders-container { margin: 20px 0; }
        .order-card { background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin: 15px 0; }
        .order-header { display: flex; justify-content: between; align-items: center; margin-bottom: 15px; }
        .order-id { font-size: 1.2em; font-weight: bold; color: #dc3545; }
        .order-date { color: #6c757d; }
        .order-details { margin: 10px 0; }
        .detail-row { margin: 8px 0; padding: 8px; background: #f8f9fa; border-radius: 4px; }
        .detail-label { font-weight: bold; color: #495057; }
        .exposed-data { background: #f8d7da !important; border-left: 4px solid #dc3545; }
        .btn { display: inline-block; padding: 8px 16px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        .btn:hover { background: #c82333; }
        .sort-controls { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 4px; }
        .vulnerability-info { background: #f8d7da; padding: 8px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 8px 0; color: #721c24; font-size: 0.9em; }
    </style>
</head>
<body>
    <a href="/" class="home-link">← Back to Home</a>
    
    <h1>🚨 My Orders - VULNERABLE Version</h1>

    <div class="warning-note">
        <h3>⚠️ Security Vulnerabilities Demonstrated:</h3>
        <ul>
            <li><strong>Insecure Direct Object Reference:</strong> Can access any user's orders</li>
            <li><strong>SQL Injection:</strong> Sort parameter vulnerable to injection</li>
            <li><strong>Information Disclosure:</strong> Exposes sensitive data and user IDs</li>
            <li><strong>No Access Control:</strong> No authorization checks</li>
            <li><strong>XSS Vulnerabilities:</strong> Unescaped output in multiple places</li>
        </ul>
    </div>

    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- VULNERABLE: SQL injection in sort parameter -->
    <div class="sort-controls">
        <strong>Sort Orders By:</strong>
        <a href="?sort=id" class="btn">ID</a>
        <a href="?sort=created_at" class="btn">Date</a>
        <a href="?sort=user_id" class="btn">User ID</a>
        <!-- Malicious example: ?sort=id; DROP TABLE orders; -- -->
        <div class="vulnerability-info">🚨 Try: ?sort=id UNION SELECT 1,2,3,4,5--</div>
    </div>

    <div class="orders-container">
        @if(empty($orders))
            <p>No orders found.</p>
        @else
            @foreach($orders as $order)
                <div class="order-card">
                    <div class="order-header">
                        <div class="order-id">Order #{!! $order['id'] !!}</div>
                        <div class="order-date">{!! $order['created_at'] !!}</div>
                    </div>

                    <!-- VULNERABLE: Exposed user ID -->
                    <div class="detail-row exposed-data">
                        <span class="detail-label">🚨 User ID Exposed:</span>
                        <span>{!! $order['user_id'] !!}</span>
                        <div class="vulnerability-info">This reveals internal user identifiers</div>
                    </div>

                    <div class="order-details">
                        @if(isset($order['data']))
                            <!-- VULNERABLE: XSS in product name -->
                            <div class="detail-row">
                                <span class="detail-label">Product:</span>
                                <span>{!! $order['data']['product_name'] ?? 'N/A' !!}</span>
                            </div>

                            <div class="detail-row">
                                <span class="detail-label">Quantity:</span>
                                <span>{!! $order['data']['quantity'] ?? 'N/A' !!}</span>
                            </div>

                            <!-- VULNERABLE: Sensitive data exposed in plain text -->
                            <div class="detail-row exposed-data">
                                <span class="detail-label">🚨 Delivery Address (Plain Text):</span>
                                <div>{!! $order['data']['delivery_address'] ?? 'N/A' !!}</div>
                                <div class="vulnerability-info">Should be encrypted and masked</div>
                            </div>

                            <div class="detail-row exposed-data">
                                <span class="detail-label">🚨 Phone Number (Plain Text):</span>
                                <span>{!! $order['data']['phone_number'] ?? 'N/A' !!}</span>
                                <div class="vulnerability-info">PII exposed without encryption</div>
                            </div>

                            <div class="detail-row exposed-data">
                                <span class="detail-label">🚨 Credit Card (Plain Text!):</span>
                                <span>{!! $order['data']['credit_card_number'] ?? 'N/A' !!}</span>
                                <div class="vulnerability-info">CRITICAL: Credit card data should never be stored unencrypted!</div>
                            </div>
                        @endif
                    </div>

                    <!-- VULNERABLE: Direct object reference -->
                    <a href="/order-vuln/{!! $order['id'] !!}" class="btn">View Details (Vulnerable)</a>
                </div>
            @endforeach
        @endif
    </div>

    <div style="text-align: center; margin-top: 30px;">
        <a href="/order-vuln" class="btn">Place New Order (Vulnerable)</a>
        <a href="/logout" class="btn btn-secondary">Logout</a>
    </div>

    <!-- VULNERABLE: Debug information exposure -->
    <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px;">
        <h4>🚨 Debug Information (Should be hidden in production):</h4>
        <p><strong>Total Orders Shown:</strong> {{ count($orders) }}</p>
        <p><strong>Current URL:</strong> {{ request()->fullUrl() }}</p>
        <p><strong>User Agent:</strong> {{ request()->userAgent() }}</p>
        <p><strong>IP Address:</strong> {{ request()->ip() }}</p>
        <p><strong>Session Data:</strong> {!! json_encode(session()->all()) !!}</p>
    </div>

    <script>
        // VULNERABLE: Client-side data exposure
        window.ordersData = {!! json_encode($orders) !!};
        console.log('Orders data exposed in JavaScript:', window.ordersData);
        
        // VULNERABLE: XSS demonstration
        document.addEventListener('DOMContentLoaded', function() {
            // This allows any injected script in the data to execute
            console.log('Page loaded - check for XSS payloads in order data');
        });
    </script>
</body>
</html>