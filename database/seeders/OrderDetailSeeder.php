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
        //
    }
    public function createDetails($orderId, $orderStatus)
    {
        $services = DB::table('services')->get()->keyBy('name');
        $parts = DB::table('parts')->get()->keyBy('name');

        $total = 0;

        // 🔥 chọn 1–3 service chính
        $selectedServices = $services->random(rand(1, 3));

        foreach ($selectedServices as $service) {

            // SERVICE
            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'item_type' => 'App\Models\Service',
                'item_id' => $service->id,
                'quantity' => 1,
                'price' => $service->price,
                'discount' => 0,
                'status' => $this->mapStatus($orderStatus),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $total += $service->price;

            // 🔥 LOGIC COMBO THỰC TẾ

            // Thay dầu → luôn có lọc dầu + dầu
            if ($service->name === 'Thay dầu động cơ') {

                $this->addPart($orderId, $parts, 'Lọc dầu động cơ', 1, $total);
                $this->addPart($orderId, $parts, 'Dầu động cơ 5W30', rand(3, 5), $total);
            }

            // Phanh → má phanh
            if ($service->name === 'Kiểm tra hệ thống phanh') {

                $this->addPart($orderId, $parts, 'Má phanh trước', 1, $total);
            }

            // Điều hòa → gas lạnh
            if ($service->name === 'Vệ sinh khoang máy') {

                if (rand(0, 1)) {
                    $this->addPart($orderId, $parts, 'Gas lạnh R134a', 1, $total);
                }
            }
        }

        // 🔥 random thêm part ngoài
        for ($i = 0; $i < rand(0, 3); $i++) {

            $part = $parts->random();

            $quantity = rand(1, 2);

            $discount = rand(0, 100) < 20 ? rand(10000, 50000) : 0;

            $amount = ($part->price_sell * $quantity) - $discount;

            $total += $amount;

            DB::table('order_details')->insert([
                'order_id' => $orderId,
                'item_type' => 'App\Models\Part',
                'item_id' => $part->id,
                'quantity' => $quantity,
                'price' => $part->price_sell,
                'discount' => $discount,
                'status' => $this->mapStatus($orderStatus),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('orders')
            ->where('id', $orderId)
            ->update(['total_amount' => $total]);
    }

    private function addPart($orderId, $parts, $name, $quantity, &$total)
    {
        if (!$parts->has($name)) return;

        $part = $parts[$name];

        $amount = $part->price_sell * $quantity;

        $total += $amount;

        DB::table('order_details')->insert([
            'order_id' => $orderId,
            'item_type' => 'App\Models\Part',
            'item_id' => $part->id,
            'quantity' => $quantity,
            'price' => $part->price_sell,
            'discount' => 0,
            'status' => 'IN_PROGRESS',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function mapStatus($orderStatus)
    {
        return match ($orderStatus) {
            'RECEIVED' => 'PENDING',
            'QUOTED' => 'CONFIRMED',
            'IN_PROGRESS' => 'IN_PROGRESS',
            'WAITING_PART' => 'PENDING',
            'COMPLETED' => 'COMPLETED',
            'PAID' => 'COMPLETED',
            default => 'PENDING',
        };
    }
}
