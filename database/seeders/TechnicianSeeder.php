<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Seeders này không cần chạy vì Technician đã được tạo trong UserSeeder
     * Giữ lại để reference hoặc update lại nếu cần
     */
    public function run(): void
    {
        // Technicians được tạo trong UserSeeder đã, không cần seed lại

        // Nếu muốn thêm technicians khác:
        // $users = DB::table('users')
        //     ->where('role', 'TECHNICIAN')
        //     ->get();
        //
        // foreach ($users as $user) {
        //     DB::table('technicians')->insert([
        //         'user_id' => $user->id,
        //         'name' => 'Technician ' . $user->id,
        //         'phone' => '09000000' . $user->id,
        //         'specialization' => 'Engine Repair',
        //         'is_actived' => true,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }
    }
}
