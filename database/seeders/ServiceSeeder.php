<?php

namespace Database\Seeders;

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
                'name' => 'Thay dầu động cơ & kiểm tra lọc dầu',
                'icon' => 'booking-icon/dau.png',
                'price' => 400000,
                'estimated_duration' => 30,
                'description' => 'Thay dầu động cơ và kiểm tra lọc dầu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kiểm tra & bảo dưỡng hệ thống phanh',
                'icon' => 'booking-icon/phanh.png',
                'price' => 300000,
                'estimated_duration' => 45,
                'description' => 'Kiểm tra má phanh, đĩa phanh và dầu phanh',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kiểm tra & thay thế ắc quy',
                'icon' => 'booking-icon/ac-quy.png',
                'price' => 150000,
                'estimated_duration' => 20,
                'description' => 'Kiểm tra và thay thế ắc quy',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kiểm tra tổng thể bằng máy chuyên dụng',
                'icon' => 'booking-icon/bao-tri.png',
                'price' => 500000,
                'estimated_duration' => 60,
                'description' => 'Kiểm tra tổng thể xe theo tiêu chuẩn hãng',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Dịch vụ lốp & cân bằng động bánh xe',
                'icon' => 'booking-icon/thay-lop.png',
                'price' => 100000,
                'estimated_duration' => 30,
                'description' => 'Tháo và lắp lốp mới, cân bằng bánh xe',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cân chỉnh thước lái',
                'icon' => 'booking-icon/thuoc-lai.png',
                'price' => 300000,
                'estimated_duration' => 45,
                'description' => 'Làm sạch khoang động cơ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bảo dưỡng hệ thống điều hòa',
                'icon' => 'booking-icon/dieu-hoa.png',
                'price' => 400000,
                'estimated_duration' => 60,
                'description' => 'Làm sạch khoang động cơ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bảo dưỡng & thay dầu hộp số',
                'icon' => 'booking-icon/hop-so.png',
                'price' => 800000,
                'estimated_duration' => 90,
                'description' => 'Làm sạch khoang động cơ',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
