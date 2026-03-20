<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $customers = DB::table('customers')->pluck('id')->toArray();
        $cars = DB::table('cars')->pluck('id')->toArray();
        $bookings = DB::table('bookings')->pluck('id')->toArray();

        // Lấy technician kèm branch_id từ user
        $technicians = DB::table('technicians')
            ->join('users', 'technicians.user_id', '=', 'users.id')
            ->select(
                'technicians.id as technician_id',
                'users.branch_id as branch_id'
            )
            ->get()
            ->toArray();

        $branches = DB::table('branches')->pluck('id')->toArray();

        $statuses = [
            'RECEIVED',
            'QUOTED',
            'IN_PROGRESS',
            'WAITING_PART',
            'COMPLETED',
            'PAID',
            'CANCELLED'
        ];

        for ($i = 0; $i < 400; $i++) {

            // 60% có technician
            $hasTechnician = rand(0, 10) > 4;

            $technicianId = null;
            $branchId = null;

            if ($hasTechnician) {
                $tech = $technicians[array_rand($technicians)];

                $technicianId = $tech->technician_id;

                // 🔥 branch phải theo technician
                $branchId = $tech->branch_id;
            } else {
                // Không có technician thì random branch
                $branchId = $branches[array_rand($branches)];
            }

            DB::table('orders')->insert([
                'fuel_range' => rand(10, 90),

                'status' => $statuses[array_rand($statuses)],

                'total_amount' => rand(0, 5000000),

                'description' => fake()->optional()->sentence(),
                'note' => fake()->optional()->sentence(),

                'user_id' => $users[array_rand($users)],
                'branch_id' => $branchId,
                'car_id' => $cars[array_rand($cars)],
                'customer_id' => $customers[array_rand($customers)],

                // 70% order có booking
                'booking_id' => rand(0, 10) > 3
                    ? $bookings[array_rand($bookings)]
                    : null,

                'technician_id' => $technicianId,

                'created_at' => now()->subDays(rand(0, 10)),
                'updated_at' => now(),
            ]);
        }
    }
}
