<?php

namespace Database\Seeders;

use App\Models\Cart;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cart = Cart::create([
            'user_id' => 1,
            'customer_name' => 'Mohammed',
            'total_price' => 0.00,
        ]);

        $cart->items()->createMany([
            ['cartable_type' => 'App\Models\Service', 'cartable_id' => 1, 'unit_price' => 29.99, 'booking_date' => now()->addDays(1), 'time_slot_id' => 1],
            ['cartable_type' => 'App\Models\Service', 'cartable_id' => 2, 'unit_price' => 39.99, 'booking_date' => now()->addDays(1), 'time_slot_id' => 2],
        ]);
    }
}
