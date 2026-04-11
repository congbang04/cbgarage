<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $cars = DB::table('cars')->get();
        $branches = DB::table('branches')->get();

        // Map technicians by branch for better distribution
        $techniciansByBranch = [];
        foreach ($branches as $branch) {
            $techniciansByBranch[$branch->id] = DB::table('technicians')
                ->join('users', 'technicians.user_id', '=', 'users.id')
                ->where('users.branch_id', $branch->id)
                ->select('technicians.id')
                ->pluck('id')
                ->toArray();
        }

        // Order statuses flow
        $statusesFlow = [
            'RECEIVED',      // 20%
            'QUOTED',        // 15%
            'IN_PROGRESS',   // 30%
            'WAITING_PART',  // 10%
            'COMPLETED',     // 20%
            'PAID'           // 5%
        ];

        $descriptions = [
            'Khách báo xe bị rung khi chạy tốc độ cao',
            'Xe phát tiếng kêu lạ khi phanh',
            'Bảo dưỡng định kỳ 10.000 km',
            'Điều hòa không mát, cần nạp gas',
            'Xe hao xăng bất thường',
            'Kiểm tra tổng quát trước chuyến đi xa',
            'Thay dầu động cơ và kiểm tra lọc',
            'Phanh kém phản ứng',
            'Gioăng động cơ bị rò rỉ',
            'Sửa chữa hệ thống điện',
            'Kiểm tra và bảo dưỡng hệ thống treo',
            'Cân chỉnh thước lái',
        ];

        $baseDate = Carbon::now()->subDays(60);
        $currentDate = $baseDate->copy();

        foreach ($cars as $index => $car) {
            // Mỗi xe có 3-8 orders (thực tế hơn)
            // Xe cuối cùng được yên cầu ngay hôm nay để dashboard có dữ liệu
            $orderCount = ($index === count($cars) - 1) ? 2 : rand(3, 8);

            for ($i = 0; $i < $orderCount; $i++) {
                // Timeline tiến dần (orders cũ hơn có trạng thái COMPLETED/PAID)
                // Xe cuối cùng: order 1 từ hôm nay, order 2 từ 3 ngày trước
                if ($index === count($cars) - 1) {
                    $daysOffset = ($i === 0) ? 0 : 3;
                } else {
                    $daysOffset = ($index * 2) + ($i * 7) + rand(0, 6);
                }

                $createdAt = (clone $baseDate)->addDays($daysOffset);

                // Xác định branch từ user địa chỉ (nếu có)
                $branchId = DB::table('users')
                    ->where('id', $car->customer_id)
                    ->value('branch_id') ?? 1;

                // Phân bổ realisric status dựa trên timeline
                if ($createdAt->isToday()) {
                    // Orders hôm nay: mix các trạng thái để dashboard đẹp
                    $status = $faker->randomElement([
                        'RECEIVED',      // 25% - xe vừa nhận
                        'QUOTED',        // 25% - đã báo giá
                        'IN_PROGRESS',   // 30% - đang sửa
                        'WAITING_PART'   // 20% - đang chờ phụ tùng
                    ]);
                } elseif ($createdAt->diffInDays(now()) > 30) {
                    // Orders cũ (> 30 ngày) đa phần COMPLETED/PAID
                    $status = $faker->randomElement(['COMPLETED', 'COMPLETED', 'COMPLETED', 'PAID', 'PAID']);
                } elseif ($createdAt->diffInDays(now()) > 10) {
                    // Orders trung bình (10-30 ngày) có mix
                    $status = $faker->randomElement(['COMPLETED', 'COMPLETED', 'PAID', 'IN_PROGRESS', 'QUOTED']);
                } else {
                    // Orders mới (< 10 ngày)
                    $status = $faker->randomElement(['RECEIVED', 'RECEIVED', 'QUOTED', 'IN_PROGRESS', 'COMPLETED']);
                }

                // Chọn technician từ branch đó
                $technicianId = null;
                if (!empty($techniciansByBranch[$branchId])) {
                    $technicianId = $techniciansByBranch[$branchId][array_rand($techniciansByBranch[$branchId])];
                }

                $orderId = DB::table('orders')->insertGetId([
                    'fuel_range' => rand(10, 90),
                    'status' => $status,
                    'total_amount' => 0, // Will be calculated in OrderDetailSeeder
                    'description' => $descriptions[array_rand($descriptions)],
                    'note' => $faker->optional(0.4)->sentence(),

                    'user_id' => $car->customer_id,
                    'branch_id' => $branchId,
                    'car_id' => $car->id,
                    'customer_id' => $car->customer_id,

                    'booking_id' => null,
                    'technician_id' => $technicianId,

                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addDays(rand(0, 10)),
                ]);

                // Create order details
                app(OrderDetailSeeder::class)->createDetails($orderId, $status);
            }
        }
    }
}
