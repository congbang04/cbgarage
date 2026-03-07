<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'name' => 'Thay dầu động cơ',
                'price' => 350000,
                'estimated_duration' => 30,
                'description' => 'Thay dầu động cơ và kiểm tra lọc dầu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bảo dưỡng định kỳ',
                'price' => 1200000,
                'estimated_duration' => 120,
                'description' => 'Kiểm tra tổng thể xe theo tiêu chuẩn hãng',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kiểm tra hệ thống phanh',
                'price' => 250000,
                'estimated_duration' => 45,
                'description' => 'Kiểm tra má phanh, đĩa phanh và dầu phanh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Thay lốp xe',
                'price' => 150000,
                'estimated_duration' => 30,
                'description' => 'Tháo và lắp lốp mới, cân bằng bánh xe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vệ sinh khoang máy',
                'price' => 300000,
                'estimated_duration' => 60,
                'description' => 'Làm sạch khoang động cơ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
