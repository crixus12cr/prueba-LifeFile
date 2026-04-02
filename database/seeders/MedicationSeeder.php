<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medications = [];
        
        // Medicamentos con lote 951357
        for($i = 1; $i <= 10; $i++) {
            $medications[] = [
                'name' => 'Paracetamol ' . (500 + ($i * 50)) . 'mg',
                'lot_number' => '951357',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        for($i = 1; $i <= 10; $i++) {
            $medications[] = [
                'name' => 'Ibuprofeno ' . (400 + ($i * 50)) . 'mg',
                'lot_number' => '951357',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        for($i = 1; $i <= 10; $i++) {
            $medications[] = [
                'name' => 'Amoxicilina ' . (500 + ($i * 100)) . 'mg',
                'lot_number' => '951357',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Otros lotes
        for($i = 1; $i <= 5; $i++) {
            $medications[] = [
                'name' => 'Loratadina ' . (10 + $i) . 'mg',
                'lot_number' => '789012',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        for($i = 1; $i <= 5; $i++) {
            $medications[] = [
                'name' => 'Omeprazol ' . (20 + $i) . 'mg',
                'lot_number' => '345678',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach($medications as $medication) {
            Medication::create($medication);
        }
    }
}