<?php

namespace Database\Seeders;

use App\Models\PartRequest;
use App\Models\PartRequestDetail;
use App\Models\Order;
use App\Models\Part;
use App\Models\Branch;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PartRequestSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');
        $branches = Branch::all();
        $parts = Part::all();

        if ($branches->isEmpty() || $parts->isEmpty()) {
            $this->command->error('Không đủ dữ liệu Branch hoặc Part để seed.');
            return;
        }

        // Các trạng thái chuẩn từ Model PartRequest
        $validStatuses = [
            PartRequest::STATUS_PENDING,
            PartRequest::STATUS_COMPLETED,
            PartRequest::STATUS_REJECTED,
        ];

        $realisticNotes = [
            'Cần thay thế gấp để giao xe khách',
            'Khách hàng yêu cầu dùng phụ tùng chính hãng',
            'Phụ tùng bảo dưỡng định kỳ theo mốc',
            'Thay thế theo khuyến cáo sau khi kiểm tra',
            'Chờ duyệt từ quản lý kho',
            'Phụ tùng thay thế cho phần máy gầm',
            'Thay thế linh kiện điện tử hao mòn',
            'Khách đồng ý báo giá, tiến hành xuất kho',
            'Phụ tùng không có sẵn, cần đặt hàng',
            'Yêu cầu vật tư khẩn cấp',
        ];

        foreach ($branches as $branch) {
            // Lấy các order thuộc chi nhánh này và đã gán kỹ thuật viên
            $ordersInBranch = Order::where('branch_id', $branch->id)
                ->whereNotNull('technician_id')
                ->get();

            if ($ordersInBranch->isEmpty()) {
                $this->command->warn("Chi nhánh {$branch->name} không có Order hợp lệ. Bỏ qua...");
                continue;
            }

            // Tạo đúng 10 record cho mỗi chi nhánh
            for ($i = 0; $i < 10; $i++) {
                $order = $ordersInBranch->random();

                // Logic thời gian: Request nằm trong khoảng từ lúc tạo Order đến lúc cập nhật cuối
                $startDate = Carbon::parse($order->created_at);
                $endDate = Carbon::parse($order->updated_at ?? $order->created_at);
                if ($endDate->lessThan($startDate)) $endDate = $startDate->copy()->addHours(2);

                $requestDate = Carbon::instance($faker->dateTimeBetween($startDate, $endDate));

                // Logic trạng thái dựa trên thời gian thực tế (Giống logic cũ nhưng dùng chuẩn const)
                $daysAgo = now()->diffInDays($requestDate);
                if ($daysAgo > 14) {
                    $status = $faker->randomElement([PartRequest::STATUS_COMPLETED, PartRequest::STATUS_REJECTED]);
                } else {
                    $status = $faker->randomElement([PartRequest::STATUS_PENDING, PartRequest::STATUS_COMPLETED]);
                }

                $partRequest = PartRequest::create([
                    'order_id'      => $order->id,
                    'technician_id' => $order->technician_id,
                    'branch_id'     => $branch->id,
                    'status'        => $status,
                    'note'          => $faker->randomElement($realisticNotes),
                    'created_at'    => $requestDate,
                    'updated_at'    => $status === PartRequest::STATUS_PENDING ? $requestDate : $requestDate->copy()->addHours(rand(1, 5)),
                ]);

                // Tạo chi tiết phụ tùng (1-3 loại)
                $numParts = rand(1, 3);
                $selectedParts = $parts->random(min($numParts, $parts->count()));

                foreach ($selectedParts as $part) {
                    PartRequestDetail::create([
                        'request_id' => $partRequest->id,
                        'part_id'    => $part->id,
                        'quantity'   => rand(1, 5),
                        'created_at' => $requestDate,
                        'updated_at' => $requestDate,
                    ]);
                }
            }
        }

        $this->command->info('Đã hoàn thành seed PartRequest cho tất cả chi nhánh.');
    }
}
