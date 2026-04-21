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

        // Lấy toàn bộ service bao gồm id và estimated_duration để tính end_time
        $services = DB::table('services')->select('id', 'estimated_duration')->get();
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
                // Khách đã có thông tin trong hệ thống
                $customer = $customers->random();
                $customerId = $customer->id;
                $contactName = $customer->name;
                $contactPhone = $customer->phone;

                $cars = DB::table('cars')->where('customer_id', $customerId)->pluck('id')->toArray();
                if (!empty($cars)) {
                    $carId = $cars[array_rand($cars)];
                }
            } else {
                // Khách lẻ tự đặt trên web, chỉ để lại Tên + SĐT
                $contactName = $faker->firstName() . ' ' . $faker->lastName();
                $contactPhone = $this->fakeVietnamPhone($faker);
            }

            // 1. Chọn ngẫu nhiên 1 dịch vụ để lấy ID và thời gian thực hiện
            $service = $services->random();
            $serviceId = $service->id;
            $duration = $service->estimated_duration;

            // 2. Logic random thời gian bắt đầu đảm bảo kết thúc KHÔNG QUÁ 18h
            $daysOffset = rand(-30, 14);

            do {
                // Giờ chỉ random từ 8h đến 17h (vì nếu lấy 18h cộng thêm duration chắc chắn sẽ lố giờ)
                $hour = rand(8, 17);
                $minuteOptions = [0, 20, 40];
                $minute = $minuteOptions[array_rand($minuteOptions)];

                // Tính tổng số phút từ 00:00 trong ngày để kiểm tra
                // 18h00 tương đương với 18 * 60 = 1080 phút
                $totalMinutes = ($hour * 60) + $minute + $duration;
            } while ($totalMinutes > 18 * 60); // Nếu lố 18h thì vòng lặp sẽ tự động bốc lại giờ khác

            $start = $baseDate->copy()
                ->addDays($daysOffset)
                ->setTime($hour, $minute, 0);

            // 3. Tính thời gian kết thúc dựa vào thời gian làm dịch vụ
            $end = (clone $start)->addMinutes($duration);

            // LOGIC TRẠNG THÁI
            if ($start <= now()) {
                $status = $faker->randomElement([
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CANCELED
                ]);
            } else {
                $status = $faker->randomElement([
                    Booking::STATUS_PENDING,
                    Booking::STATUS_PENDING,
                    Booking::STATUS_PENDING,
                    Booking::STATUS_CANCELED
                ]);
            }

            DB::table('bookings')->insert([
                'contact_name' => $contactName,
                'contact_phone' => $contactPhone,
                'service_id' => $serviceId,
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
