<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $branches = DB::table('branches')->pluck('id')->toArray();
        $customers = DB::table('customers')->pluck('id')->toArray();
        $cars = DB::table('cars')->pluck('id')->toArray();
        $bookings = DB::table('bookings')->pluck('id')->toArray();
        $technicians = DB::table('technicians')->pluck('id')->toArray();

        $statuses = [
            'RECEIVED',
            'QUOTED',
            'IN_PROGRESS',
            'WAITING_PART',
            'COMPLETED',
            'PAID',
            'CANCELLED'
        ];

        for ($i = 0; $i < 45; $i++) {

            DB::table('orders')->insert([

                'fuel_range' => rand(10, 90),

                'status' => $statuses[array_rand($statuses)],

                'total_amount' => rand(0, 5000000),

                'description' => fake()->optional()->sentence(),
                'note' => fake()->optional()->sentence(),

                'user_id' => $users[array_rand($users)],
                'branch_id' => $branches[array_rand($branches)],
                'car_id' => $cars[array_rand($cars)],
                'customer_id' => $customers[array_rand($customers)],

                // 70% order có booking
                'booking_id' => rand(0, 10) > 3
                    ? $bookings[array_rand($bookings)]
                    : null,

                // 60% có technician
                'technician_id' => rand(0, 10) > 4
                    ? $technicians[array_rand($technicians)]
                    : null,

                'created_at' => now()->subDays(rand(0, 10)),
                'updated_at' => now(),
            ]);
        }
    }
}
