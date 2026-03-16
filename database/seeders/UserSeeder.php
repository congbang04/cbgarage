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
        /*
        |--------------------------------
        | SUPER ADMIN (Admin tổng)
        |--------------------------------
        */
        User::create([
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('Rcvn123456'),
            'role' => 'SUPER_ADMIN',
            'branch_id' => null,
            'is_actived' => true,
            'is_deleted' => false,
            'email_verified_at' => now(),
        ]);

        /*
        |--------------------------------
        | ADMIN CHI NHÁNH
        |--------------------------------
        */
        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'email' => "admin{$i}@garage.com",
                'password' => Hash::make('Rcvn123456'),
                'role' => 'BRANCH_ADMIN',
                'branch_id' => $i,
                'is_actived' => true,
                'is_deleted' => false,
                'email_verified_at' => now(),
            ]);
        }

        /*
        |--------------------------------
        | TECHNICIANS
        |--------------------------------
        */
        for ($i = 1; $i <= 5; $i++) {

            $user = User::create([
                'email' => "tech{$i}@garage.com",
                'password' => Hash::make('Rcvn123456'),
                'role' => 'TECHNICIAN',
                'branch_id' => rand(1, 3),
                'is_actived' => true,
                'is_deleted' => false,
                'email_verified_at' => now(),
            ]);

            DB::table('technicians')->insert([
                'user_id' => $user->id,
                'name' => "Technician {$i}",
                'phone' => '09000000' . $i,
                'specialization' => 'Engine Repair',
                'is_actived' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        /*
        |--------------------------------
        | CUSTOMERS
        |--------------------------------
        */
        for ($i = 1; $i <= 4; $i++) {
            User::create([
                'email' => "customer{$i}@gmail.com",
                'password' => Hash::make('Rcvn123456'),
                'role' => 'CUSTOMER',
                'branch_id' => null,
                'is_actived' => true,
                'is_deleted' => false,
                'email_verified_at' => now(),
            ]);
        }
    }
}
