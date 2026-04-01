<?php

namespace Database\Seeders;

use App\Models\Medication;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ordersData = [
            [
                'customer_id' => 1, 
                'purchase_date' => Carbon::now()->subDays(5), 
                'medications' => [1, 2]
            ],
            [
                'customer_id' => 2, 
                'purchase_date' => Carbon::now()->subDays(10), 
                'medications' => [1]
            ],
            [
                'customer_id' => 3, 
                'purchase_date' => Carbon::now()->subDays(15), 
                'medications' => [2]
            ],
            [
                'customer_id' => 4, 
                'purchase_date' => Carbon::now()->subDays(20), 
                'medications' => [1, 2]
            ],
            [
                'customer_id' => 1, 
                'purchase_date' => Carbon::now()->subDays(40), 
                'medications' => [1]
            ],
        ];
        
        foreach($ordersData as $orderData) {
            $order = Order::create([
                'customer_id' => $orderData['customer_id'],
                'purchase_date' => $orderData['purchase_date'],
            ]);
            
            foreach($orderData['medications'] as $medId) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'medication_id' => $medId,
                ]);
            }
        }
    }
}
