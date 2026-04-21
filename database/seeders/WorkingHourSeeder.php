<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkingHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workingHours = [
            0 => true,  // Chủ nhật
            1 => true,  // Thứ 2
            2 => true,  // Thứ 3
            3 => true,  // Thứ 4
            4 => true,  // Thứ 5 nghỉ thì sửa thành false
            5 => true,  // Thứ 6
            6 => true,  // Thứ 7
        ];

        foreach ($workingHours as $day => $active) {

            DB::table('working_hours')->insert([
                'day_of_week' => $day,
                'active' => $active,
                'start_time' => $active ? '08:00:00' : null,
                'end_time' => $active ? '18:00:00' : null,
                'lunch_start' => $active ? '12:00:00' : null,
                'lunch_end' => $active ? '13:00:00' : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
