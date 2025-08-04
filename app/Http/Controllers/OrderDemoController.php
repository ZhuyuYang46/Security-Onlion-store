<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Order;

class OrderDemoController extends Controller
{
    // Show registration form
    public function showRegister()
    {
        return view('register');
    }

    // Handle user registration with encrypted password storage
    public function doRegister(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            // Create user with hashed password (Laravel automatically hashes due to User model cast)
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']), // Bcrypt with salt
            ]);

            // Log audit trail for user creation
            $this->logAuditTrail('users', $user->id, 'CREATE', 'User registered', [
                'name' => $user->name,
                'email' => $user->email
            ]);

            return redirect('/login-safe')->with('success', 'Registration successful! Please login.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }

    // Show order form (requires authentication)
    public function showOrder()
    {
        if (!Auth::check()) {
            return redirect('/login-safe')->with('error', 'Please login to place an order.');
        }
        
        return view('place-order');
    }

    // Handle order creation with encrypted sensitive data storage
    public function doOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/login-safe')->with('error', 'Please login to place an order.');
        }

        // Validate input
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'delivery_address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:20',
            'credit_card_number' => 'required|string|max:19',
        ]);

        try {
            // Prepare sensitive data for encryption
            $sensitiveData = [
                'product_name' => $validated['product_name'],
                'quantity' => $validated['quantity'],
                'delivery_address' => $validated['delivery_address'],
                'phone_number' => $validated['phone_number'],
                'credit_card_number' => $validated['credit_card_number'],
                'order_date' => now()->toDateTimeString(),
            ];

            // Encrypt sensitive data using Laravel's AES-256 encryption
            $encryptedInfo = Crypt::encryptString(json_encode($sensitiveData));

            // Create order with row-level security (user_id ensures isolation)
            $order = Order::create([
                'user_id' => Auth::id(), // Ensures row-level security
                'encrypted_info' => $encryptedInfo,
            ]);

            // Log audit trail for order creation
            $this->logAuditTrail('orders', $order->id, 'CREATE', 'Order placed', [
                'user_id' => Auth::id(),
                'product_name' => $validated['product_name'],
                'quantity' => $validated['quantity']
            ]);

            return redirect('/order')->with('success', 'Order placed successfully! Your sensitive data is encrypted and secure.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Order placement failed. Please try again.']);
        }
    }

    // View user's own orders (Row-Level Security implementation)
    public function viewMyOrders()
    {
        if (!Auth::check()) {
            return redirect('/login-safe')->with('error', 'Please login to view orders.');
        }

        try {
            // Row-Level Security: Only fetch orders belonging to current user
            $orders = Order::where('user_id', Auth::id())->get();

            // Decrypt order information for display
            $decryptedOrders = [];
            foreach ($orders as $order) {
                $decryptedData = json_decode(Crypt::decryptString($order->encrypted_info), true);
                $decryptedOrders[] = [
                    'id' => $order->id,
                    'created_at' => $order->created_at,
                    'data' => $decryptedData
                ];
            }

            // Log audit trail for order access
            $this->logAuditTrail('orders', null, 'READ', 'User accessed their orders', [
                'user_id' => Auth::id(),
                'orders_count' => count($orders)
            ]);

            return view('my-orders', ['orders' => $decryptedOrders]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to load orders.']);
        }
    }

    // Attempt to access specific order by ID (demonstrates Row-Level Security)
    public function viewOrder($orderId)
    {
        if (!Auth::check()) {
            return redirect('/login-safe')->with('error', 'Please login to view orders.');
        }

        try {
            // Row-Level Security: Only allow access to user's own orders
            $order = Order::where('id', $orderId)
                          ->where('user_id', Auth::id()) // Critical security check
                          ->first();

            if (!$order) {
                // Log unauthorized access attempt
                $this->logAuditTrail('orders', $orderId, 'UNAUTHORIZED_ACCESS', 'Attempted to access unauthorized order', [
                    'user_id' => Auth::id(),
                    'attempted_order_id' => $orderId
                ]);

                return redirect('/my-orders')->withErrors(['error' => 'Order not found or access denied.']);
            }

            // Decrypt order information
            $decryptedData = json_decode(Crypt::decryptString($order->encrypted_info), true);

            // Log successful order access
            $this->logAuditTrail('orders', $orderId, 'READ', 'User accessed specific order', [
                'user_id' => Auth::id(),
                'order_id' => $orderId
            ]);

            return view('order-details', [
                'order' => $order,
                'data' => $decryptedData
            ]);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to load order details.']);
        }
    }

    // Audit Trail Implementation - Records all database operations
    private function logAuditTrail($table, $recordId, $action, $description, $details = [])
    {
        try {
            DB::table('audit_logs')->insert([
                'user_id' => Auth::id(),
                'table_name' => $table,
                'record_id' => $recordId,
                'action' => $action,
                'description' => $description,
                'details' => json_encode($details),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log audit failures silently to avoid disrupting main functionality
            \Log::error('Audit trail logging failed: ' . $e->getMessage());
        }
    }

    // VULNERABLE VERSION - Demonstrates multiple security flaws
    public function showOrderVuln()
    {
        // No authentication check - anyone can access
        return view('place-order-vuln');
    }

    // VULNERABLE: Order creation with multiple security flaws
    public function doOrderVuln(Request $request)
    {
        // No authentication check
        // No CSRF protection (middleware disabled for this route)
        // No input validation
        
        try {
            // VULNERABLE: Direct SQL injection possible
            $userId = $request->input('user_id', 1); // Can be manipulated
            $productName = $request->input('product_name');
            $quantity = $request->input('quantity');
            $deliveryAddress = $request->input('delivery_address');
            $phoneNumber = $request->input('phone_number');
            $creditCardNumber = $request->input('credit_card_number');

            // VULNERABLE: Raw SQL query with string concatenation
            $sql = "INSERT INTO orders (user_id, encrypted_info, created_at, updated_at) VALUES (
                '" . $userId . "', 
                '" . json_encode([
                    'product_name' => $productName,
                    'quantity' => $quantity,
                    'delivery_address' => $deliveryAddress,
                    'phone_number' => $phoneNumber,
                    'credit_card_number' => $creditCardNumber, // Plain text storage!
                    'order_date' => now()->toDateTimeString(),
                ]) . "',
                '" . now() . "',
                '" . now() . "'
            )";

            // VULNERABLE: Execute raw SQL without parameterization
            DB::statement($sql);

            // VULNERABLE: XSS in success message
            return redirect('/order-vuln')->with('success', 
                'Order placed successfully for: <script>alert("XSS Vulnerability!")</script>' . $productName);

        } catch (\Exception $e) {
            // VULNERABLE: Error information disclosure
            return back()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    // VULNERABLE: View orders without proper access control
    public function viewMyOrdersVuln(Request $request)
    {
        try {
            // VULNERABLE: SQL injection in ORDER BY clause
            $orderBy = $request->input('sort', 'id');
            
            // VULNERABLE: No user isolation - can see all orders
            $sql = "SELECT * FROM orders ORDER BY " . $orderBy;
            $orders = DB::select($sql);

            $decryptedOrders = [];
            foreach ($orders as $order) {
                // VULNERABLE: No encryption - data stored as plain JSON
                $decryptedData = json_decode($order->encrypted_info, true);
                $decryptedOrders[] = [
                    'id' => $order->id,
                    'user_id' => $order->user_id, // Exposed user IDs
                    'created_at' => $order->created_at,
                    'data' => $decryptedData
                ];
            }

            return view('my-orders-vuln', ['orders' => $decryptedOrders]);

        } catch (\Exception $e) {
            // VULNERABLE: Detailed error exposure
            return back()->withErrors(['error' => 'SQL Error: ' . $e->getMessage() . ' Query: ' . $sql]);
        }
    }

    // VULNERABLE: Direct object reference without authorization
    public function viewOrderVuln($orderId, Request $request)
    {
        try {
            // VULNERABLE: SQL injection in WHERE clause
            $sql = "SELECT * FROM orders WHERE id = " . $orderId;
            $orders = DB::select($sql);

            if (empty($orders)) {
                return redirect('/my-orders-vuln')->withErrors(['error' => 'Order not found.']);
            }

            $order = $orders[0];
            
            // VULNERABLE: No access control - anyone can view any order
            $decryptedData = json_decode($order->encrypted_info, true);

            return view('order-details-vuln', [
                'order' => $order,
                'data' => $decryptedData
            ]);

        } catch (\Exception $e) {
            // VULNERABLE: Full error disclosure including stack trace
            return back()->withErrors(['error' => 'Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine()]);
        }
    }
}
