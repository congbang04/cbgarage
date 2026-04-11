<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $services = DB::table('services')->pluck('id')->toArray();
        $bays = DB::table('bays')->pluck('id')->toArray();

        $customers = DB::table('customers')
            ->select('id', 'name', 'phone')
            ->get();

        $baseDate = Carbon::now();

        // Tạo 80 bookings (từ -30 đến +30 ngày)
        for ($i = 0; $i < 80; $i++) {
            /** 70% customers có account - 30% khách lẻ */
            $isGuest = $faker->boolean(30);

            $customerId = null;
            $contactName = null;
            $contactPhone = null;
            $carId = null;

            if (!$isGuest && $customers->isNotEmpty()) {
                /** ✅ Khách có tài khoản */
                $customer = $customers->random();

                $customerId = $customer->id;
                $contactName = $customer->name;
                $contactPhone = $customer->phone;

                /** 🔹 Lấy xe đúng customer */
                $cars = DB::table('cars')
                    ->where('customer_id', $customerId)
                    ->pluck('id')
                    ->toArray();

                if (!empty($cars)) {
                    $carId = $cars[array_rand($cars)];
                }
            } else {
                /** ✅ Khách lẻ */
                $contactName = $faker->firstName() . ' ' . $faker->lastName();
                $contactPhone = $this->fakeVietnamPhone($faker);

                // 50% khách lẻ có xe
                if ($faker->boolean(50)) {
                    $carId = DB::table('cars')->inRandomOrder()->value('id');
                }
            }

            /** 🔹 Thời gian hợp lý: -30 ngày đến +30 ngày từ hôm nay */
            $daysOffset = rand(-30, 30);
            $start = $baseDate->copy()
                ->addDays($daysOffset)
                ->setTime(rand(8, 17), [0, 30][rand(0, 1)]);

            // Nếu quá khứ, thì CONFIRMED (75%) hoặc CANCELLED (25%)
            // Nếu tương lai, thì PENDING (66%) hoặc CONFIRMED (34%)
            if ($start < now()) {
                $status = $faker->randomElement(['CONFIRMED', 'CONFIRMED', 'CONFIRMED', 'CANCELLED']);
            } else {
                $status = $faker->randomElement(['PENDING', 'PENDING', 'CONFIRMED']);
            }

            $durationList = [30, 60, 90, 120, 150];
            $duration = $durationList[array_rand($durationList)];

            $end = (clone $start)->addMinutes($duration);

            DB::table('bookings')->insert([
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,

                'service_id' => $services[array_rand($services)],
                'start_time' => $start,
                'end_time' => $end,

                'status' => $status,
                'description' => $faker->boolean(60) ? $faker->sentence() : null,

                'bay_id' => $bays[array_rand($bays)],

                'customer_id' => $customerId,
                'car_id' => $carId,

                'created_at' => $start->copy()->subDays(rand(1, 7)),
                'updated_at' => now(),
            ]);
        }

        // Thêm 20 bookings từ hôm nay để dashboard có pending bookings
        for ($i = 0; $i < 20; $i++) {
            $isGuest = $faker->boolean(30);

            $customerId = null;
            $contactName = null;
            $contactPhone = null;
            $carId = null;

            if (!$isGuest && $customers->isNotEmpty()) {
                $customer = $customers->random();
                $customerId = $customer->id;
                $contactName = $customer->name;
                $contactPhone = $customer->phone;

                $cars = DB::table('cars')
                    ->where('customer_id', $customerId)
                    ->pluck('id')
                    ->toArray();

                if (!empty($cars)) {
                    $carId = $cars[array_rand($cars)];
                }
            } else {
                $contactName = $faker->firstName() . ' ' . $faker->lastName();
                $contactPhone = $this->fakeVietnamPhone($faker);

                if ($faker->boolean(50)) {
                    $carId = DB::table('cars')->inRandomOrder()->value('id');
                }
            }

            // Từ hôm nay đến +14 ngày
            $daysOffset = rand(0, 14);
            $start = now()->copy()
                ->addDays($daysOffset)
                ->setTime(rand(8, 17), [0, 30][rand(0, 1)]);

            // Hôm nay: PENDING (80%) hoặc CONFIRMED (20%)
            $status = $faker->randomElement(['PENDING', 'PENDING', 'PENDING', 'PENDING', 'CONFIRMED']);

            $durationList = [30, 60, 90, 120, 150];
            $duration = $durationList[array_rand($durationList)];

            $end = (clone $start)->addMinutes($duration);

            DB::table('bookings')->insert([
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,

                'service_id' => $services[array_rand($services)],
                'start_time' => $start,
                'end_time' => $end,

                'status' => $status,
                'description' => $faker->boolean(60) ? $faker->sentence() : null,

                'bay_id' => $bays[array_rand($bays)],

                'customer_id' => $customerId,
                'car_id' => $carId,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * 🔥 Fake số điện thoại VN (chỉ dùng cho khách lẻ)
     */
    private function fakeVietnamPhone($faker): string
    {
        $prefixes = [
            '032',
            '033',
            '034',
            '035',
            '036',
            '037',
            '038',
            '039',
            '070',
            '076',
            '077',
            '078',
            '079',
            '081',
            '082',
            '083',
            '084',
            '085',
            '086',
            '088',
            '089',
            '090',
            '091',
            '092',
            '093',
            '094',
            '096',
            '097',
            '098',
            '099'
        ];

        return $faker->randomElement($prefixes) . $faker->numerify('#######');
    }
}
