<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $cars = DB::table('cars')->get();

        $technicians = DB::table('technicians')
            ->join('users', 'technicians.user_id', '=', 'users.id')
            ->select(
                'technicians.id as technician_id',
                'users.branch_id as branch_id'
            )
            ->get()
            ->groupBy('branch_id');

        $statusesFlow = [
            'RECEIVED',
            'QUOTED',
            'IN_PROGRESS',
            'WAITING_PART',
            'COMPLETED',
            'PAID'
        ];

        foreach ($cars as $car) {

            // mỗi xe có 2–6 order
            $orderCount = rand(2, 6);

            for ($i = 0; $i < $orderCount; $i++) {

                $status = $statusesFlow[array_rand($statusesFlow)];

                $branchId = DB::table('users')
                    ->where('id', $car->customer_id)
                    ->value('branch_id') ?? rand(1, 3);

                $techList = $technicians[$branchId] ?? collect();

                $technicianId = $techList->isNotEmpty()
                    ? $techList->random()->technician_id
                    : null;

                $createdAt = $faker->dateTimeBetween('-60 days', 'now');

                $orderId = DB::table('orders')->insertGetId([
                    'fuel_range' => rand(10, 90),
                    'status' => $status,
                    'total_amount' => 0,
                    'description' => $this->generateDescription(),
                    'note' => $faker->optional()->sentence(),

                    'user_id' => $car->customer_id,
                    'branch_id' => $branchId,
                    'car_id' => $car->id,
                    'customer_id' => $car->customer_id,

                    'booking_id' => null,
                    'technician_id' => $technicianId,

                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                app(OrderDetailSeeder::class)->createDetails($orderId, $status);
            }
        }
    }

    private function generateDescription()
    {
        return collect([
            'Khách báo xe bị rung khi chạy tốc độ cao',
            'Xe phát tiếng kêu lạ khi phanh',
            'Bảo dưỡng định kỳ 10.000 km',
            'Điều hòa không mát',
            'Xe hao xăng bất thường',
            'Kiểm tra tổng quát trước chuyến đi xa'
        ])->random();
    }
}
