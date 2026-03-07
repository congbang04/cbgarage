<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bays')->insert([

            // Branch 2 - Quận 1
            [
                'name' => 'Bay Q1-01',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay Q1-02',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay Q1-03',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay Q1-04',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Branch 3 - Quận 7
            [
                'name' => 'Bay Q7-01',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay Q7-02',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay Q7-03',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay Q7-04',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Branch 4 - Thủ Đức
            [
                'name' => 'Bay TD-01',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay TD-02',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay TD-03',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bay TD-04',
                'status' => 'available',
                'type' => 'car',
                'branch_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
