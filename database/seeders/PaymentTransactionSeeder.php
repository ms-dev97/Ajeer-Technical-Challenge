<?php

namespace Database\Seeders;

use App\Models\PaymentTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentTransaction::insert([
            [
                'user_id' => 1,
                'gateway' => 'mada',
                'amount' => 100.00,
                'gateway_transaction_id' => 'mada_' . uniqid(),
                'status' => 'completed',
                'city' => 'Riyadh',
                'module' => 'maintenance',
            ],
            [
                'user_id' => 1,
                'gateway' => 'mada',
                'amount' => 50.00,
                'gateway_transaction_id' => 'mada_' . uniqid(),
                'status' => 'pending',
                'city' => 'Jeddah',
                'module' => 'rent',
            ],
            [
                'user_id' => 1,
                'gateway' => 'mada',
                'amount' => 75.00,
                'gateway_transaction_id' => 'mada_' . uniqid(),
                'status' => 'failed',
                'city' => 'Dammam',
                'module' => 'utilities',
            ],
        ]);
    }
}
