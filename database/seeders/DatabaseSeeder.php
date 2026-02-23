<?php

namespace Database\Seeders;

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
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'email' => 'a' . $i . '@a',
                'password' => Hash::make('Rcvn123456'),
                'is_active' => '1',
                'is_delete' => '0',
                'role' => 'TECHNICIAN',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'email' => 'admin' . $i . '@a',
                'password' => Hash::make('Rcvn123456'),
                'is_active' => '1',
                'is_delete' => '0',
                'role' => 'ADMIN',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
