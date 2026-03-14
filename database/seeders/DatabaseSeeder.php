<?php

namespace Database\Seeders;

use Database\Seeders\CustomerSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            CustomerSeeder::class,
            CarSeeder::class,
            ServiceSeeder::class,
            WorkingHourSeeder::class,
            BaySeeder::class,
            TechnicianSeeder::class,
        ]);
    }
}
