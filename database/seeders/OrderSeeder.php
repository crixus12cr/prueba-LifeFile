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
        // Obtener medicamentos con lote 951357
        $medications951357 = Medication::where('lot_number', '951357')->get();
        $medicationIds = $medications951357->pluck('id')->toArray();
        
        // Obtener todos los clientes
        $customers = Customer::all();
        
        // Crear muchas órdenes para probar paginación (al menos 50 órdenes con lote 951357)
        $ordersData = [];
        
        // Generar órdenes para los últimos 60 días
        for($day = 1; $day <= 60; $day++) {
            $purchaseDate = Carbon::now()->subDays($day);
            
            // Para cada día, crear entre 5 y 15 órdenes
            $ordersPerDay = rand(5, 15);
            
            for($i = 1; $i <= $ordersPerDay; $i++) {
                $customer = $customers->random();
                
                // 80% de probabilidad de que incluya un medicamento con lote 951357
                $hasLot951357 = rand(1, 100) <= 80;
                
                if($hasLot951357) {
                    // Incluir al menos un medicamento del lote 951357
                    $numMedications = rand(1, 4);
                    $selectedMedications = [];
                    
                    // Agregar medicamento del lote 951357
                    $selectedMedications[] = $medicationIds[array_rand($medicationIds)];
                    
                    // Agregar medicamentos adicionales (pueden ser del mismo lote o no)
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
                    // Órdenes sin el lote 951357
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
        
        // Insertar órdenes y sus items
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