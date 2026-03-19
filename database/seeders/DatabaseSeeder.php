<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BrandSeeder::class,
            BranchSeeder::class,
            CarModelSeeder::class,
            UserSeeder::class,
            CarSeeder::class,
            ServiceSeeder::class,
            WorkingHourSeeder::class,
            BaySeeder::class,
            BookingSeeder::class,
            OrderSeeder::class,
            PartSeeder::class,
            OrderDetailSeeder::class,
        ]);
    }
}
