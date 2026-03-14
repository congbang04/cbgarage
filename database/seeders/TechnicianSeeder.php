<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TechnicianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')
            ->where('role', 'TECHNICIAN')
            ->get();

        foreach ($users as $user) {
            DB::table('technicians')->insert([
                'user_id' => $user->id,
                'name' => 'Technician ' . $user->id,
                'phone' => '09000000' . $user->id,
                'specialization' => 'Engine Repair',
                'is_actived' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
