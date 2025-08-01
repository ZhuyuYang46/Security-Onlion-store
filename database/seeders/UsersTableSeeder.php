<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;
use App\Models\Order;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users with known passwords
        $user1 = User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user2 = User::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'password' => Hash::make('password123'),
        ]);

        $user3 = User::create([
            'name' => 'Carol Davis',
            'email' => 'carol@example.com',
            'password' => Hash::make('password123'),
        ]);

        // User with no orders to demonstrate empty state
        $user4 = User::create([
            'name' => 'Dave Wilson',
            'email' => 'dave@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Create encrypted orders for user1 (Alice)
        $aliceOrders = [
            [
                'product_name' => 'MacBook Pro 16"',
                'quantity' => 1,
                'delivery_address' => '123 Tech Street, San Francisco, CA 94102',
                'phone_number' => '+1-415-555-0101',
                'credit_card_number' => '4532-1234-5678-9012',
                'order_date' => '2025-07-28 10:30:00',
            ],
            [
                'product_name' => 'iPhone 15 Pro',
                'quantity' => 2,
                'delivery_address' => '123 Tech Street, San Francisco, CA 94102',
                'phone_number' => '+1-415-555-0101',
                'credit_card_number' => '4532-1234-5678-9012',
                'order_date' => '2025-07-29 14:20:00',
            ],
            [
                'product_name' => 'AirPods Pro',
                'quantity' => 1,
                'delivery_address' => '456 Innovation Ave, Palo Alto, CA 94301',
                'phone_number' => '+1-650-555-0201',
                'credit_card_number' => '5555-4444-3333-2222',
                'order_date' => '2025-07-30 09:15:00',
            ]
        ];

        foreach ($aliceOrders as $orderData) {
            Order::create([
                'user_id' => $user1->id,
                'encrypted_info' => Crypt::encryptString(json_encode($orderData)),
                'created_at' => $orderData['order_date'],
                'updated_at' => $orderData['order_date'],
            ]);
        }

        // Create encrypted orders for user2 (Bob)
        $bobOrders = [
            [
                'product_name' => 'Gaming Desktop PC',
                'quantity' => 1,
                'delivery_address' => '789 Gaming Blvd, Austin, TX 73301',
                'phone_number' => '+1-512-555-0301',
                'credit_card_number' => '4111-1111-1111-1111',
                'order_date' => '2025-07-25 16:45:00',
            ],
            [
                'product_name' => 'Mechanical Keyboard',
                'quantity' => 1,
                'delivery_address' => '789 Gaming Blvd, Austin, TX 73301',
                'phone_number' => '+1-512-555-0301',
                'credit_card_number' => '4111-1111-1111-1111',
                'order_date' => '2025-07-26 11:30:00',
            ]
        ];

        foreach ($bobOrders as $orderData) {
            Order::create([
                'user_id' => $user2->id,
                'encrypted_info' => Crypt::encryptString(json_encode($orderData)),
                'created_at' => $orderData['order_date'],
                'updated_at' => $orderData['order_date'],
            ]);
        }

        // Create one order for user3 (Carol)
        $carolOrder = [
            'product_name' => 'Smart Watch',
            'quantity' => 1,
            'delivery_address' => '321 Wellness Way, Denver, CO 80202',
            'phone_number' => '+1-303-555-0401',
            'credit_card_number' => '3782-822463-10005',
            'order_date' => '2025-07-31 13:20:00',
        ];

        Order::create([
            'user_id' => $user3->id,
            'encrypted_info' => Crypt::encryptString(json_encode($carolOrder)),
            'created_at' => $carolOrder['order_date'],
            'updated_at' => $carolOrder['order_date'],
        ]);

        echo "Created 4 test users and 6 encrypted orders:\n";
        echo "- alice@example.com (password123) - 3 orders\n";
        echo "- bob@example.com (password123) - 2 orders\n";
        echo "- carol@example.com (password123) - 1 order\n";
        echo "- dave@example.com (password123) - 0 orders (for testing empty state)\n";
    }
}
