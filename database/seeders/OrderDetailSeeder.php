<?php

namespace Database\Seeders;

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
        $services = DB::table('services')->get();
        $parts = DB::table('parts')->get();

        $total = 0;

        // Chọn 1–3 services chính
        $serviceCount = rand(1, 3);
        $selectedServices = $services->random(min($serviceCount, count($services)));

        // BƯỚC 1: LUÔN GÁN SERVICE (Vì lúc tiếp nhận khách đã báo muốn làm gì)
        foreach ($selectedServices as $service) {
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
        }

        // BƯỚC 2: CHỈ GÁN PHỤ TÙNG KHI TRẠNG THÁI ĐÃ VƯỢT QUA MỨC 'RECEIVED'
        // (Tương đương với việc đã lên báo giá - QUOTED trở đi)
        if ($orderStatus !== 'RECEIVED') {

            // Lặp lại các service để match phụ tùng tương ứng
            foreach ($selectedServices as $service) {
                $this->addRelevantParts($orderId, $service->name, $parts, $total, $orderStatus);
            }

            // Thêm 0-3 parts random ngoài combo
            for ($i = 0; $i < rand(0, 3); $i++) {
                $randomly = $parts->random();
                $quantity = rand(1, 3);
                $amount = ($randomly->price_sell * $quantity);
                $total += $amount;

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'item_type' => 'App\Models\Part',
                    'item_id' => $randomly->id,
                    'quantity' => $quantity,
                    'price' => $randomly->price_sell,
                    'status' => $this->mapStatus($orderStatus),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Cập nhật tổng tiền vào Order
        DB::table('orders')
            ->where('id', $orderId)
            ->update(['total_amount' => $total]);
    }

    /**
     * Thêm parts tương ứng với service
     */
    private function addRelevantParts($orderId, $serviceName, $parts, &$total, $orderStatus)
    {
        $partMappings = [
            'Thay dầu động cơ' => ['Lọc dầu động cơ', 'Dầu động cơ 5W30', 'Dầu động cơ 10W40'],
            'Kiểm tra hệ thống phanh' => ['Má phanh trước', 'Má phanh sau', 'Dầu phanh DOT4'],
            'Kiểm tra & thay thế ắc quy' => ['Ắc quy 12V'],
            'Dịch vụ lốp' => ['Lốp xe 15 inch', 'Lốp xe 16 inch', 'Lốp xe 17 inch', 'Van lốp'],
            'Bảo dưỡng hệ thống điều hòa' => ['Gas lạnh R134a', 'Lốc điều hòa'],
            'Bảo dưỡng & thay dầu hộp số' => ['Dầu hộp số tự động ATF', 'Dầu hộp số sàn'],
        ];

        $relevantParts = [];

        // Try direct match first
        if (isset($partMappings[$serviceName])) {
            $relevantParts = $partMappings[$serviceName];
        } else {
            // Try partial match
            foreach ($partMappings as $serviceKey => $partNames) {
                if (
                    str_contains(strtolower($serviceName), strtolower($serviceKey)) ||
                    str_contains(strtolower($serviceKey), strtolower($serviceName))
                ) {
                    $relevantParts = $partNames;
                    break;
                }
            }
        }

        // Thêm relevant parts
        if (!empty($relevantParts)) {
            $count = rand(1, min(2, count($relevantParts)));
            $selectedPartNames = array_slice($relevantParts, 0, $count);

            foreach ($selectedPartNames as $partName) {
                $part = $parts->firstWhere('name', $partName);
                if ($part) {
                    $quantity = rand(1, 2);
                    $amount = $part->price_sell * $quantity;
                    $total += $amount;

                    DB::table('order_details')->insert([
                        'order_id' => $orderId,
                        'item_type' => 'App\Models\Part',
                        'item_id' => $part->id,
                        'quantity' => $quantity,
                        'price' => $part->price_sell,
                        'discount' => 0,
                        'status' => $this->mapStatus($orderStatus),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
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
