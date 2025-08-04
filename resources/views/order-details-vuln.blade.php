<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Vulnerable Version</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 700px; margin: 50px auto; padding: 20px; }
        .alert { padding: 10px; margin: 10px 0; border-radius: 4px; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .home-link { display: inline-block; margin-bottom: 20px; color: #007bff; text-decoration: none; }
        .home-link:hover { text-decoration: underline; }
        .order-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 8px; padding: 30px; }
        .order-header { text-align: center; margin-bottom: 30px; }
        .order-id { font-size: 1.5em; font-weight: bold; color: #dc3545; }
        .order-date { color: #6c757d; margin-top: 5px; }
        .details-section { margin: 20px 0; }
        .section-title { font-weight: bold; color: #495057; margin-bottom: 15px; border-bottom: 2px solid #dc3545; padding-bottom: 5px; }
        .detail-row { margin: 12px 0; padding: 10px; background: white; border-radius: 4px; }
        .detail-label { font-weight: bold; color: #495057; }
        .detail-value { margin-left: 10px; }
        .exposed-data { background: #f8d7da !important; padding: 8px; border-radius: 4px; border-left: 4px solid #dc3545; }
        .warning-note { background: #f8d7da; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px; margin: 5px; }
        .btn:hover { background: #c82333; }
        .btn-secondary { background: #6c757d; }
        .btn-secondary:hover { background: #545b62; }
        .vulnerability-info { background: #f8d7da; padding: 8px; border: 1px solid #f5c6cb; border-radius: 4px; margin: 8px 0; color: #721c24; font-size: 0.9em; }
        .sql-injection-demo { background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 20px 0; }
    </style>
</head>
<body>
    <a href="/my-orders-vuln" class="home-link">← Back to My Orders (Vulnerable)</a>
    
    <h1>🚨 Order Details - VULNERABLE Version</h1>

    <div class="warning-note">
        <h3>⚠️ Security Vulnerabilities Demonstrated:</h3>
        <ul>
            <li><strong>Insecure Direct Object Reference:</strong> Can view any order by changing ID</li>
            <li><strong>SQL Injection:</strong> Order ID parameter vulnerable</li>
            <li><strong>No Access Control:</strong> No authorization checks</li>
            <li><strong>Information Disclosure:</strong> Exposes sensitive data and internal IDs</li>
            <li><strong>XSS Vulnerabilities:</strong> Unescaped output throughout</li>
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

    @if(isset($order) && isset($data))
        <div class="order-card">
            <div class="order-header">
                <!-- VULNERABLE: XSS in order ID -->
                <div class="order-id">Order #{!! $order->id !!}</div>
                <div class="order-date">{!! \Carbon\Carbon::parse($order->created_at)->format('F j, Y \a\t g:i A') !!}</div>
            </div>

            <!-- VULNERABLE: Exposed internal database structure -->
            <div class="details-section">
                <div class="section-title">🚨 Internal Database Information (Should be hidden)</div>
                
                <div class="detail-row exposed-data">
                    <span class="detail-label">Database ID:</span>
                    <span class="detail-value">{!! $order->id !!}</span>
                    <div class="vulnerability-info">Internal database IDs should not be exposed</div>
                </div>
                
                <div class="detail-row exposed-data">
                    <span class="detail-label">User ID:</span>
                    <span class="detail-value">{!! $order->user_id !!}</span>
                    <div class="vulnerability-info">Reveals which user owns this order</div>
                </div>

                <div class="detail-row exposed-data">
                    <span class="detail-label">Raw Database Timestamps:</span>
                    <div class="detail-value">
                        Created: {!! $order->created_at !!}<br>
                        Updated: {!! $order->updated_at !!}
                    </div>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">📦 Product Information</div>
                
                <!-- VULNERABLE: XSS in product name -->
                <div class="detail-row">
                    <span class="detail-label">Product Name:</span>
                    <span class="detail-value">{!! $data['product_name'] !!}</span>
                    <div class="vulnerability-info">Unescaped output - XSS possible</div>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Quantity:</span>
                    <span class="detail-value">{!! $data['quantity'] !!}</span>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">🚚 Delivery Information (UNENCRYPTED!)</div>
                
                <div class="detail-row exposed-data">
                    <span class="detail-label">🚨 Delivery Address (Plain Text):</span>
                    <div class="detail-value">{!! $data['delivery_address'] !!}</div>
                    <div class="vulnerability-info">CRITICAL: Should be encrypted in database</div>
                </div>
                
                <div class="detail-row exposed-data">
                    <span class="detail-label">🚨 Phone Number (Plain Text):</span>
                    <span class="detail-value">{!! $data['phone_number'] !!}</span>
                    <div class="vulnerability-info">PII should be encrypted and masked</div>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">💳 Payment Information (CRITICAL VULNERABILITY!)</div>
                
                <div class="detail-row exposed-data">
                    <span class="detail-label">🚨 Credit Card Number (PLAIN TEXT!):</span>
                    <span class="detail-value">{!! $data['credit_card_number'] !!}</span>
                    <div class="vulnerability-info">CRITICAL: Credit card data should NEVER be stored unencrypted!</div>
                </div>
            </div>

            <div class="details-section">
                <div class="section-title">📅 Order Timeline</div>
                
                <div class="detail-row">
                    <span class="detail-label">Order Date:</span>
                    <span class="detail-value">{!! $data['order_date'] !!}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Database Record Created:</span>
                    <span class="detail-value">{!! $order->created_at !!}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Last Updated:</span>
                    <span class="detail-value">{!! $order->updated_at !!}</span>
                </div>
            </div>
        </div>

        <div class="sql-injection-demo">
            <h4>🚨 SQL Injection Demonstration</h4>
            <p>The order ID in the URL is vulnerable to SQL injection. Try these examples:</p>
            <ul>
                <li><code>/order-vuln/1 UNION SELECT 1,2,'{"fake":"data"}',now(),now()--</code></li>
                <li><code>/order-vuln/1; DROP TABLE orders;--</code></li>
                <li><code>/order-vuln/1 OR 1=1--</code></li>
            </ul>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="/my-orders-vuln" class="btn">← Back to All Orders (Vulnerable)</a>
            <a href="/order-vuln" class="btn btn-secondary">Place New Order (Vulnerable)</a>
        </div>

        <!-- VULNERABLE: Sensitive data exposed in HTML comments -->
        <!-- 
        DEBUG INFO:
        Order ID: {!! $order->id !!}
        User ID: {!! $order->user_id !!}
        Full data: {!! json_encode($data) !!}
        -->

        <div style="margin-top: 30px; padding: 15px; background: #f1f3f4; border-radius: 8px; font-size: 0.9em;">
            <strong>🚨 Vulnerabilities Demonstrated:</strong>
            <ul>
                <li>Credit card data stored in plain text (PCI DSS violation)</li>
                <li>Personal information unencrypted (GDPR/privacy violation)</li>
                <li>SQL injection in URL parameter</li>
                <li>Cross-site scripting (XSS) in multiple fields</li>
                <li>Insecure direct object reference (IDOR)</li>
                <li>Information disclosure in error messages</li>
                <li>No access control or authorization</li>
            </ul>
        </div>
    @endif

    <script>
        // VULNERABLE: Expose sensitive data in JavaScript
        window.vulnerableOrderData = {
            orderId: '{!! $order->id ?? 0 !!}',
            userId: '{!! $order->user_id ?? 0 !!}',
            sensitiveData: {!! json_encode($data ?? []) !!}
        };
        
        console.log('🚨 VULNERABLE: Sensitive order data exposed in JavaScript:', window.vulnerableOrderData);
        
        // VULNERABLE: DOM-based XSS possibility
        document.addEventListener('DOMContentLoaded', function() {
            // This could execute malicious scripts if data contains XSS payloads
            var orderInfo = window.vulnerableOrderData;
            console.log('Order loaded:', orderInfo);
        });
    </script>
</body>
</html>