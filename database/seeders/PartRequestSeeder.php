<?php

namespace Database\Seeders;

use App\Models\PartRequest;
use App\Models\PartRequestDetail;
use App\Models\Order;
use App\Models\Part;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PartRequestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        // Lấy các order hợp lệ (có gán kỹ thuật viên) để đảm bảo tính logic dữ liệu
        $orders = Order::whereNotNull('technician_id')->get();
        $parts = Part::all();

        if ($orders->isEmpty() || $parts->isEmpty()) {
            $this->command->warn('Không đủ dữ liệu Order hoặc Part để chạy seeder. Vui lòng chạy OrderSeeder và PartSeeder trước.');
            return;
        }

        $statuses = PartRequest::statuses();

        $realisticNotes = [
            'Cần thay thế gấp để giao xe khách',
            'Khách hàng yêu cầu dùng phụ tùng chính hãng',
            'Phụ tùng bảo dưỡng định kỳ theo mốc',
            'Thay thế theo khuyến cáo sau khi kiểm tra',
            'Chờ duyệt từ quản lý kho',
            'Phụ tùng thay thế cho phần máy gầm',
            'Thay thế linh kiện điện tử hao mòn',
            'Khách đồng ý báo giá, tiến hành xuất kho',
            null // Đôi khi kỹ thuật viên không ghi chú
        ];

        $totalRecords = 45;

        for ($i = 0; $i < $totalRecords; $i++) {
            // Chọn ngẫu nhiên 1 order
            $order = $orders->random();
            $status = $statuses[array_rand($statuses)];

            // Thời gian tạo request phải từ lúc tạo order cho đến hiện tại
            $orderDate = Carbon::parse($order->created_at);
            $createdAt = $faker->dateTimeBetween($orderDate, 'now');

            // Tạo Part Request
            $partRequest = PartRequest::create([
                'order_id'      => $order->id,
                'technician_id' => $order->technician_id,
                'branch_id'     => $order->branch_id, // Lấy branch_id trực tiếp từ order để đồng bộ
                'status'        => $status,
                'note'          => $faker->randomElement($realisticNotes),
                'created_at'    => $createdAt,
                'updated_at'    => $createdAt,
            ]);

            // Chọn ngẫu nhiên 1 đến 4 loại phụ tùng cho request này
            $numParts = rand(1, 4);
            $selectedParts = $parts->random($numParts);

            // Thêm chi tiết phụ tùng (PartRequestDetail)
            foreach ($selectedParts as $part) {
                PartRequestDetail::create([
                    'request_id' => $partRequest->id,
                    'part_id'    => $part->id,
                    'quantity'   => rand(1, 5),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        $this->command->info("Đã seed thành công {$totalRecords} Part Requests chuẩn production!");
    }
}
