<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('branches')->insert([
            [
                'name' => 'Kho Trung Tâm CB Garage',
                'address' => '123 Quốc lộ 13, Thủ Đức, TP.HCM',
                'is_central_warehouse' => true,
                'phone' => '02838889999',
                'coordinates' => '10.849902,106.771546',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CB Garage Quận 1',
                'address' => '25 Nguyễn Huệ, Quận 1, TP.HCM',
                'is_central_warehouse' => false,
                'phone' => '02837778888',
                'coordinates' => '10.776889,106.700806',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CB Garage Quận 7',
                'address' => '88 Nguyễn Thị Thập, Quận 7, TP.HCM',
                'is_central_warehouse' => false,
                'phone' => '02836667777',
                'coordinates' => '10.732367,106.720112',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CB Garage Thủ Đức',
                'address' => '45 Võ Văn Ngân, Thủ Đức, TP.HCM',
                'is_central_warehouse' => false,
                'phone' => '02835556666',
                'coordinates' => '10.851221,106.758221',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
