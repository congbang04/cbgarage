<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');
        $branches = DB::table('branches')->get();

        // Cache technician theo chi nhánh
        $techniciansByBranch = [];
        foreach ($branches as $branch) {
            $techniciansByBranch[$branch->id] = DB::table('technicians')
                ->join('users', 'technicians.user_id', '=', 'users.id')
                ->where('users.branch_id', $branch->id)
                ->pluck('technicians.id')
                ->toArray();
        }

        // Cache danh sách xe (Mỗi xe đã chứa sẵn customer_id là chủ xe hợp lệ từ CarSeeder)
        $cars = DB::table('cars')->get();

        // ==========================================
        // PHA 1: TẠO ORDER TỪ BOOKING CONFIRMED 
        // ==========================================
        $confirmedBookings = DB::table('bookings')
            ->where('status', Booking::STATUS_CONFIRMED)
            ->get();

        foreach ($confirmedBookings as $booking) {
            $orderCustomerId = $booking->customer_id;
            $orderCarId = $booking->car_id;

            if (!$orderCarId || !$orderCustomerId) {
                $randomCar = $cars->random();
                $orderCarId = $randomCar->id;
                $orderCustomerId = $randomCar->customer_id;
            }

            $branchId = DB::table('users')->where('id', $orderCustomerId)->value('branch_id') ?? 1;

            $technicianId = null;
            if (!empty($techniciansByBranch[$branchId])) {
                $technicianId = $techniciansByBranch[$branchId][array_rand($techniciansByBranch[$branchId])];
            }

            $createdAt = Carbon::parse($booking->start_time);
            $status = $this->determineOrderStatus($createdAt, $faker);

            $orderId = DB::table('orders')->insertGetId([
                'fuel_range' => rand(10, 90),
                'status' => $status,
                'total_amount' => 0,
                'description' => $booking->description ?? 'Bảo dưỡng theo lịch đặt',
                'note' => 'Tiếp nhận từ mã đặt lịch: #' . $booking->id,
                'user_id' => $orderCustomerId,
                'branch_id' => $branchId,
                'car_id' => $orderCarId,             // Xe chuẩn
                'customer_id' => $orderCustomerId,   // Khớp 100% với chủ xe ở trên
                'booking_id' => $booking->id,
                'technician_id' => $technicianId,
                'created_at' => $createdAt,
                'updated_at' => clone $createdAt->addDays(rand(0, 3)),
            ]);

            app(OrderDetailSeeder::class)->createDetails($orderId, $status);
        }

        // ==========================================
        // PHA 2: TẠO ORDER KHÁCH VÃNG LAI WALK-IN
        // ==========================================
        $baseDate = Carbon::now()->subDays(60);

        // Lấy ngẫu nhiên 30 xe để tạo order vãng lai
        $walkInCars = $cars->random(min(30, $cars->count()));

        foreach ($walkInCars as $car) {
            $walkInCount = rand(1, 2);

            for ($i = 0; $i < $walkInCount; $i++) {
                $daysOffset = rand(0, 60);
                $createdAt = (clone $baseDate)->addDays($daysOffset);

                $branchId = DB::table('users')->where('id', $car->customer_id)->value('branch_id') ?? 1;
                $status = $this->determineOrderStatus($createdAt, $faker);

                $technicianId = null;
                if (!empty($techniciansByBranch[$branchId])) {
                    $technicianId = $techniciansByBranch[$branchId][array_rand($techniciansByBranch[$branchId])];
                }

                $orderId = DB::table('orders')->insertGetId([
                    'fuel_range' => rand(10, 90),
                    'status' => $status,
                    'total_amount' => 0,
                    'description' => $faker->randomElement(['Xe báo lỗi động cơ', 'Thay nhớt định kỳ', 'Kiểm tra phanh', 'Máy lạnh không mát']),
                    'note' => 'Khách vãng lai (Không qua đặt lịch)',
                    'user_id' => $car->customer_id,
                    'branch_id' => $branchId,
                    'car_id' => $car->id,                   // Xe vãng lai
                    'customer_id' => $car->customer_id,     // Luôn luôn là chủ của chiếc xe đó
                    'booking_id' => null,
                    'technician_id' => $technicianId,
                    'created_at' => $createdAt,
                    'updated_at' => clone $createdAt->addDays(rand(0, 3)),
                ]);

                app(OrderDetailSeeder::class)->createDetails($orderId, $status);
            }
        }
    }

    private function determineOrderStatus(Carbon $createdAt, $faker): string
    {
        if ($createdAt->isToday()) {
            return $faker->randomElement([Order::STATUS_RECEIVED, Order::STATUS_QUOTED, Order::STATUS_IN_PROGRESS]);
        } elseif ($createdAt->diffInDays(now()) > 30) {
            return $faker->randomElement([Order::STATUS_COMPLETED, Order::STATUS_PAID, Order::STATUS_PAID]);
        } else {
            return $faker->randomElement([Order::STATUS_RECEIVED, Order::STATUS_IN_PROGRESS, Order::STATUS_COMPLETED]);
        }
    }
}
