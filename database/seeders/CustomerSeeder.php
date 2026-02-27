<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::where('email', 'bangle161004@gmail.com')->first();
        $user2 = User::where('email', 'customer2@gmail.com')->first();

        if ($user1) {
            Customer::create([
                'user_id' => $user1->id,
                'name' => 'Lê Công Bằng',
                'phone'   => '0373456789',
                'address' => 'TP.HCM',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($user2) {
            Customer::create([
                'user_id' => $user2->id,
                'name' => 'Nguyễn Văn A',
                'phone'   => '0900000002',
                'address' => 'Đồng Nai',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
