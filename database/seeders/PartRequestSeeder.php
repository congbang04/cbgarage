<?php

namespace Database\Seeders;

use App\Models\PartRequest;
use App\Models\PartRequestDetail;
use App\Models\Order;
use App\Models\Part;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PartRequestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        // Lấy các order hợp lệ (có gán kỹ thuật viên) để đảm bảo tính logic dữ liệu
        $orders = Order::whereNotNull('technician_id')->inRandomOrder()->limit(50)->get();
        $parts = Part::all();

        if ($orders->isEmpty() || $parts->isEmpty()) {
            echo 'Không đủ dữ liệu Order hoặc Part.' . PHP_EOL;
            return;
        }

        $statuses = ['PENDING', 'APPROVED', 'REJECTED', 'COMPLETED'];

        $realisticNotes = [
            'Cần thay thế gấp để giao xe khách',
            'Khách hàng yêu cầu dùng phụ tùng chính hãng',
            'Phụ tùng bảo dưỡng định kỳ theo mốc',
            'Thay thế theo khuyến cáo sau khi kiểm tra',
            'Chờ duyệt từ quản lý kho',
            'Phụ tùng thay thế cho phần máy gầm',
            'Thay thế linh kiện điện tử hao mòn',
            'Khách đồng ý báo giá, tiến hành xuất kho',
            'Phụ tùng không có sẵn, cần đặt hàng',
            'Yêu cầu vật tư khẩn cấp',
        ];

        foreach ($orders as $order) {
            $faker_date = \Faker\Factory::create('vi_VN');

            // Part Request được tạo sau khi order được tạo
            // Ensure end date is after start date
            $startDate = Carbon::parse($order->created_at);
            $endDate = Carbon::parse($order->updated_at ?? $order->created_at)->max($startDate);

            $requestDate = $faker_date->dateTimeBetween($startDate, $endDate);

            // Status flow logic: Requests cũ → COMPLETED/APPROVED, mới → PENDING/APPROVED
            $daysAgo = now()->diffInDays($requestDate);
            if ($daysAgo > 14) {
                $status = $faker->randomElement(['COMPLETED', 'COMPLETED', 'APPROVED']);
            } elseif ($daysAgo > 7) {
                $status = $faker->randomElement(['APPROVED', 'APPROVED', 'PENDING']);
            } else {
                $status = $faker->randomElement(['PENDING', 'PENDING', 'APPROVED', 'COMPLETED']);
            }

            $partRequest = PartRequest::create([
                'order_id'      => $order->id,
                'technician_id' => $order->technician_id,
                'branch_id'     => $order->branch_id,
                'status'        => $status,
                'note'          => $faker->randomElement($realisticNotes),
                'created_at'    => $requestDate,
                'updated_at'    => $requestDate,
            ]);

            // Chọn 1-3 loại phụ tùng cho request
            $numParts = rand(1, 3);
            $selectedParts = $parts->random(min($numParts, count($parts)));

            foreach ($selectedParts as $part) {
                PartRequestDetail::create([
                    'request_id' => $partRequest->id,
                    'part_id'    => $part->id,
                    'quantity'   => rand(1, 5),
                    'created_at' => $requestDate,
                    'updated_at' => $requestDate,
                ]);
            }
        }
    }
}
