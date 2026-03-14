<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'bangle161004@gmail.com',
            'password' => Hash::make('Rcvn123456'),
            'is_actived' => '1',
            'is_deleted' => '0',
            'role' => 'CUSTOMER',
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ]);

        User::create([
            'email' => 'customer2@gmail.com',
            'password' => Hash::make('Rcvn123456'),
            'is_actived' => '1',
            'is_deleted' => '0',
            'role' => 'CUSTOMER',
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ]);
        User::create([
            'email' => 'customer3@gmail.com',
            'password' => Hash::make('Rcvn123456'),
            'is_actived' => '1',
            'is_deleted' => '0',
            'role' => 'CUSTOMER',
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ]);
        User::create([
            'email' => 'customer4@gmail.com',
            'password' => Hash::make('Rcvn123456'),
            'is_actived' => '1',
            'is_deleted' => '0',
            'role' => 'CUSTOMER',
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
        ]);
        for ($i = 1; $i <= 3; $i++) {
            DB::table('users')->insert([
                'email' => 'a' . $i . '@a',
                'password' => Hash::make('Rcvn123456'),
                'is_actived' => '1',
                'is_deleted' => '0',
                'role' => 'TECHNICIAN',
                'branch_id' => $i + 1,
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
                'branch_id' => $i + 1,
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ]);
        }
    }
}
