<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Medication;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        User::create([
            'username' => 'admin',
            'password' => Hash::make('password123'),
        ]);

        $badMedication = Medication::create(['name' => 'Ibuprofeno Compuesto', 'lot_number' => '951357']);
        $goodMedication = Medication::create(['name' => 'Paracetamol 500mg', 'lot_number' => '112233']);
        $otherMedication = Medication::create(['name' => 'Amoxicilina 250mg', 'lot_number' => '445566']);
        $medications = [$badMedication, $goodMedication, $otherMedication];

        // --- CREAR 8 CLIENTES DISTINTOS ---
        $c1 = Customer::create(['name' => 'Jurado de Evaluación', 'email' => 'nicolassalazar2k19@gmail.com', 'phone' => '+1-555-098-7654']);
        $c2 = Customer::create(['name' => 'Maria Garcia', 'email' => 'maria@example.com', 'phone' => '+1-555-111-2233']);
        $c3 = Customer::create(['name' => 'Carlos Lopez', 'email' => 'carlos@example.com', 'phone' => '+1-555-444-5566']);
        $c4 = Customer::create(['name' => 'Lucia Fernandez', 'email' => 'lucia@example.com', 'phone' => '+1-555-777-8899']);
        
        $c5 = Customer::create(['name' => 'Roberto Sanchez', 'email' => 'roberto@example.com', 'phone' => '+1-555-333-2211']);
        $c6 = Customer::create(['name' => 'Elena Gomez', 'email' => 'elena@example.com', 'phone' => '+1-555-999-0011']);
        $c7 = Customer::create(['name' => 'Fernando Torres', 'email' => 'fernando@example.com', 'phone' => '+1-555-666-4444']);
        $c8 = Customer::create(['name' => 'Isabel Ramirez', 'email' => 'isabel@example.com', 'phone' => '+1-555-222-7777']);

        $badClients = [$c1, $c2, $c3, $c4];
        $goodClients = [$c5, $c6, $c7, $c8];

        // --- Cree 20 ordenes para prubeas (10 del lote malo, 10 de otros lotes)
        
        $allClients = array_merge($badClients, $goodClients);
        
        // 10 órdenes con el lote peligroso
        for ($i = 0; $i < 10; $i++) {
            $client = $faker->randomElement($allClients);
            $order = Order::create([
                'customer_id' => $client->id,
                'purchase_date' => Carbon::now()->subMonths(rand(0, 12))->subDays(rand(1, 30)),
            ]);
            OrderItem::create([
                'order_id' => $order->id,
                'medication_id' => $badMedication->id
            ]);
        }

        // 10 órdenes con lotes sanos
        for ($i = 0; $i < 10; $i++) {
            $client = $faker->randomElement($allClients);
            $order = Order::create([
                'customer_id' => $client->id,
                'purchase_date' => Carbon::now()->subMonths(rand(0, 12))->subDays(rand(1, 30)),
            ]);
            OrderItem::create([
                'order_id' => $order->id,
                'medication_id' => $faker->randomElement([$goodMedication, $otherMedication])->id
            ]);
        }
    }
}
