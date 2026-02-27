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
            CarModelSeeder::class,
            UserSeeder::class,
            CustomerSeeder::class,
            CarSeeder::class,
        ]);
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'email' => 'a' . $i . '@a',
                'password' => Hash::make('Rcvn123456'),
                'is_actived' => '1',
                'is_deleted' => '0',
                'role' => 'TECHNICIAN',
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ]);
        }
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'email' => 'admin' . $i . '@a',
                'password' => Hash::make('Rcvn123456'),
                'is_actived' => '1',
                'is_deleted' => '0',
                'role' => 'ADMIN',
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ]);
        }
    }
}
