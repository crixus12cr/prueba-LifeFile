<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Medication;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Get medications with lot 951357
        $medications951357 = Medication::where('lot_number', '951357')->get();
        $medicationIds = $medications951357->pluck('id')->toArray();
        
        // Get all customers
        $customers = Customer::all();
        
        // Create many orders to test pagination (at least 50 orders with lot 951357)
        $ordersData = [];
        
        // Generate orders for the last 60 days
        for($day = 1; $day <= 60; $day++) {
            $purchaseDate = Carbon::now()->subDays($day);
            
            // For each day, create between 5 and 15 orders
            $ordersPerDay = rand(5, 15);
            
            for($i = 1; $i <= $ordersPerDay; $i++) {
                $customer = $customers->random();
                
                // 80% probability to include a medication with lot 951357
                $hasLot951357 = rand(1, 100) <= 80;
                
                if($hasLot951357) {
                    // Include at least one medication from lot 951357
                    $numMedications = rand(1, 4);
                    $selectedMedications = [];
                    
                    // Add medication from lot 951357
                    $selectedMedications[] = $medicationIds[array_rand($medicationIds)];
                    
                    // Add additional medications (may be from the same lot or not)
                    for($j = 1; $j < $numMedications; $j++) {
                        $allMedications = Medication::all()->pluck('id')->toArray();
                        $selectedMedications[] = $allMedications[array_rand($allMedications)];
                    }
                    
                    $ordersData[] = [
                        'customer_id' => $customer->id,
                        'purchase_date' => $purchaseDate,
                        'medications' => $selectedMedications,
                    ];
                } else {
                    // Orders without lot 951357
                    $numMedications = rand(1, 3);
                    $otherMedications = Medication::where('lot_number', '!=', '951357')->pluck('id')->toArray();
                    
                    if(!empty($otherMedications)) {
                        $selectedMedications = [];
                        for($j = 0; $j < $numMedications; $j++) {
                            $selectedMedications[] = $otherMedications[array_rand($otherMedications)];
                        }
                        
                        $ordersData[] = [
                            'customer_id' => $customer->id,
                            'purchase_date' => $purchaseDate,
                            'medications' => $selectedMedications,
                        ];
                    }
                }
            }
        }
        
        // Insert orders and their items
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