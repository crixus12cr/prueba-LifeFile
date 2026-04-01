<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => 'Juan Pérez', 
                'email' => 'juan@example.com', 
                'phone' => '3001234567'
            ],
            [
                'name' => 'María García', 
                'email' => 'maria@example.com', 
                'phone' => '3007654321'
            ],
            [
                'name' => 'Carlos López', 
                'email' => 'carlos@example.com', 
                'phone' => '3012345678'
            ],
            [
                'name' => 'Ana Martínez', 
                'email' => 'ana@example.com', 
                'phone' => '3023456789'
            ],
        ];
        
        foreach($customers as $customer) {
            Customer::create($customer);
        }
    }
}
