<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Technician;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
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
| STAFF BY ROLE
|--------------------------------
*/
        $staffPerRole = [
            'TECHNICIAN' => 40,
            'ADVISOR' => 10,
            'WAREHOUSE' => 6,
            'CASHIER' => 6,
            'BRANCH_ADMIN' => 4,
        ];

        $specializations = [
            'Kỹ thuật động cơ',
            'Kỹ thuật điện - điện tử ô tô',
            'Hệ thống phanh & an toàn',
            'Hệ thống treo & lái',
            'Kỹ thuật hộp số',
            'Hệ thống điều hòa không khí',
            'Bảo dưỡng & bảo trì định kỳ',
            'Chẩn đoán & kiểm tra lỗi',
            'Sửa chữa thân vỏ & đồng sơn',
            'Dịch vụ lốp & cân chỉnh'
        ];

        $techIndex = 1;

        for ($branch = 1; $branch <= 3; $branch++) {

            foreach ($staffPerRole as $role => $quantity) {

                for ($i = 1; $i <= $quantity; $i++) {

                    $user = User::create([
                        'email' => "staff.{$techIndex}." . strtolower($role) . "@garage.com",
                        'password' => Hash::make('Rcvn123456'),
                        'role' => $role,
                        'branch_id' => $branch,
                        'is_actived' => true,
                        'is_deleted' => false,
                        'email_verified_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
                        'created_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
                        'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
                    ]);

                    Technician::create([
                        'user_id' => $user->id,
                        'name' => fake()->name(),
                        'phone' => fake()->numerify('0#########'),
                        'specialization' => $specializations[array_rand($specializations)],
                        'is_actived' => true,
                        'created_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
                        'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
                    ]);


                    $techIndex++;
                }
            }
        }

        /*
        |--------------------------------
        | CUSTOMERS
        |--------------------------------
        */
        for ($i = 1; $i <= 30; $i++) {

            $user = User::create([
                'email' => "customer{$i}@gmail.com",
                'password' => Hash::make('Rcvn123456'),
                'role' => 'CUSTOMER',
                'branch_id' => null,
                'is_actived' => true,
                'is_deleted' => false,
                'email_verified_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
                'created_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
                'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
            ]);

            Customer::create([
                'user_id' => $user->id,
                'name' => fake()->name(),
                'phone' => fake()->numerify('0#########'),
                'address' => fake()->address(),
                'created_at' => fake()->dateTimeBetween('-2 years', '-1 month'),
                'updated_at' => fake()->dateTimeBetween('-1 month', 'now'),
            ]);
        }
    }
}
