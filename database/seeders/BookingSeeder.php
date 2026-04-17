<?php

namespace Database\Seeders;

use App\Models\Booking;
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
        $customers = DB::table('customers')->select('id', 'name', 'phone')->get();

        $baseDate = Carbon::now();

        // Tạo 100 Bookings trải dài từ -30 ngày đến +14 ngày
        for ($i = 0; $i < 100; $i++) {
            // Tỷ lệ: 40% khách vãng lai (guest), 60% khách có tài khoản
            $isGuest = $faker->boolean(40);

            $customerId = null;
            $contactName = null;
            $contactPhone = null;
            $carId = null;

            if (!$isGuest && $customers->isNotEmpty()) {
                // Khách đã có thông tin trong hệ thống (Case 1)
                $customer = $customers->random();
                $customerId = $customer->id;
                $contactName = $customer->name;
                $contactPhone = $customer->phone;

                $cars = DB::table('cars')->where('customer_id', $customerId)->pluck('id')->toArray();
                if (!empty($cars)) {
                    $carId = $cars[array_rand($cars)];
                }
            } else {
                // Khách lẻ tự đặt trên web, chỉ để lại Tên + SĐT (Case 3)
                $contactName = $faker->firstName() . ' ' . $faker->lastName();
                $contactPhone = $this->fakeVietnamPhone($faker);
            }

            // Random thời gian
            $daysOffset = rand(-30, 14);
            $start = $baseDate->copy()
                ->addDays($daysOffset)
                ->setTime(rand(8, 17), [0, 30][rand(0, 1)]);

            // 🔥 LOGIC TRẠNG THÁI CHUẨN THỰC TẾ
            if ($start <= now()) {
                // Quá khứ: Hoặc khách đã đến tạo Order (CONFIRMED) hoặc không đến/hủy (CANCELED)
                // Tỉ lệ đến thực tế thường cao hơn hủy
                $status = $faker->randomElement([
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CANCELED
                ]);
            } else {
                // Tương lai: Chỉ chờ khách đến (PENDING) hoặc đã báo hủy (CANCELED)
                $status = $faker->randomElement([
                    Booking::STATUS_PENDING,
                    Booking::STATUS_PENDING,
                    Booking::STATUS_PENDING,
                    Booking::STATUS_CANCELED
                ]);
            }

            $durationList = [30, 60, 90, 120];
            $duration = $durationList[array_rand($durationList)];
            $end = (clone $start)->addMinutes($duration);

            DB::table('bookings')->insert([
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,
                'service_id' => $services[array_rand($services)],
                'start_time' => $start,
                'end_time' => $end,
                'status' => $status,
                'description' => $faker->boolean(50) ? 'Khách đặt qua hệ thống online' : null,
                'bay_id' => $bays[array_rand($bays)],
                'customer_id' => $customerId,
                'car_id' => $carId,
                'created_at' => $start->copy()->subDays(rand(1, 5)),
                'updated_at' => now(),
            ]);
        }
    }

    private function fakeVietnamPhone($faker): string
    {
        $prefixes = ['032', '033', '034', '035', '036', '037', '038', '039', '070', '076', '077', '078', '079', '081', '082', '083', '084', '085', '086', '088', '089', '090', '091', '092', '093', '094', '096', '097', '098', '099'];
        return $faker->randomElement($prefixes) . $faker->numerify('#######');
    }
}
