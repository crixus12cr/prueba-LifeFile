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
        $customers = [];
        
        // Generate 50 random customers
        $firstNames = ['Juan', 'Maria', 'Carlos', 'Ana', 'Luis', 'Sofia', 'Pedro', 'Laura', 'Diego', 'Isabella'];
        $lastNames = ['Perez', 'Garcia', 'Rodriguez', 'Martinez', 'Lopez', 'Gonzalez', 'Sanchez', 'Ramirez', 'Torres', 'Flores'];
        $domains = ['gmail.com', 'hotmail.com', 'yahoo.com', 'outlook.com', 'example.com'];
        
        for($i = 1; $i <= 50; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = $firstName . ' ' . $lastName;
            $email = strtolower($firstName . '.' . $lastName . $i . '@' . $domains[array_rand($domains)]);
            $phone = '3' . rand(0, 9) . rand(10000000, 99999999);
            
            $customers[] = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        foreach($customers as $customer) {
            Customer::create($customer);
        }
    }
}