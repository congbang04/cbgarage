<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $services = DB::table('services')->pluck('id')->toArray();
        $customers = DB::table('customers')->pluck('id')->toArray();
        $cars = DB::table('cars')->pluck('id')->toArray();
        $bays = DB::table('bays')->pluck('id')->toArray();

        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        for ($i = 0; $i < 30; $i++) {

            $start = Carbon::now()->addDays(rand(-5, 5))->setTime(rand(8, 16), 0);
            $end = (clone $start)->addHour();

            DB::table('bookings')->insert([
                'contact_name' => fake()->name(),
                'contact_phone' => fake()->phoneNumber(),
                'service_id' => $services[array_rand($services)],
                'start_time' => $start,
                'end_time' => $end,
                'status' => $statuses[array_rand($statuses)],
                'description' => fake()->optional()->sentence(),

                'bay_id' => $bays[array_rand($bays)],
                'customer_id' => $customers[array_rand($customers)],
                'car_id' => $cars[array_rand($cars)],

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
