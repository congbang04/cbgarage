<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = DB::table('orders')->pluck('id')->toArray();
        $services = DB::table('services')->pluck('id')->toArray();
        $parts = DB::table('parts')->pluck('id')->toArray();

        $statuses = [
            'PENDING',
            'CONFIRMED',
            'IN_PROGRESS',
            'COMPLETED',
            'CANCELLED'
        ];

        foreach ($orders as $orderId) {

            // mỗi order có 1 - 5 items
            $itemCount = rand(1, 5);

            for ($i = 0; $i < $itemCount; $i++) {

                $isService = rand(0, 1);

                if ($isService) {
                    $itemType = 'SERVICE';
                    $itemId = $services[array_rand($services)];
                    $quantity = 1; // service thường = 1
                } else {
                    $itemType = 'PART';
                    $itemId = $parts[array_rand($parts)];
                    $quantity = rand(1, 3);
                }

                $price = rand(100000, 2000000);
                $discount = rand(0, 1) ? rand(0, 50000) : 0;

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'item_type' => $itemType,
                    'item_id' => $itemId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'status' => $statuses[array_rand($statuses)],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
