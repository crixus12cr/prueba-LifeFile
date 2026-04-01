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
        $medications = [
            [
                'name' => 'Paracetamol 500mg', 
                'lot_number' => '951357'
            ],
            [
                'name' => 'Ibuprofeno 400mg', 
                'lot_number' => '951357'
            ],
            [
                'name' => 'Amoxicilina 500mg', 
                'lot_number' => '123456'
            ],
            [
                'name' => 'Loratadina 10mg', 
                'lot_number' => '789012'
            ],
        ];
        
        foreach($medications as $medication) {
            Medication::create($medication);
        }
    }
}
