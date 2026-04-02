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

        $statuses = ['PENDING', 'CONFIRMED', 'CANCELLED'];

        for ($i = 0; $i < 50; $i++) {

            /** 🔥 70% có account - 30% khách lẻ */
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

                if (empty($cars)) {
                    continue; // bỏ nếu không có xe
                }

                $carId = $cars[array_rand($cars)];
            } else {
                /** ✅ Khách lẻ */
                $contactName = $faker->name();
                $contactPhone = $this->fakeVietnamPhone($faker);

                // có thể có hoặc không có xe
                $carId = DB::table('cars')->inRandomOrder()->value('id');
            }

            /** 🔹 Thời gian hợp lý */
            $start = Carbon::now()
                ->addDays(rand(-3, 5))
                ->setTime(rand(8, 16), [0, 30][rand(0, 1)]);

            $durationList = [30, 60, 90, 120];
            $duration = $durationList[array_rand($durationList)];

            $end = (clone $start)->addMinutes($duration);

            DB::table('bookings')->insert([
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,

                'service_id' => $services[array_rand($services)],
                'start_time' => $start,
                'end_time' => $end,

                'status' => $statuses[array_rand($statuses)],
                'description' => $faker->boolean(70) ? $faker->sentence() : null,

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
