<?php

namespace Database\Seeders;

use App\Models\StockTransfer;
use App\Models\Branch;
use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockTransferSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        // Lấy kho trung tâm, các chi nhánh thường, danh sách user và phụ tùng
        $centralBranch = Branch::where('is_central_warehouse', true)->first();
        $normalBranches = Branch::where('is_central_warehouse', false)->get();

        // Lấy danh sách ID của user để gán cho người tạo phiếu (created_by)
        $users = User::pluck('id');
        $parts = Part::all();

        // Kiểm tra dữ liệu đầu vào
        if (!$centralBranch || $normalBranches->isEmpty() || $users->isEmpty() || $parts->isEmpty()) {
            $this->command->warn('Không đủ dữ liệu (Branch, User, Part) để chạy StockTransferSeeder. Vui lòng chạy các seeder khác trước.');
            return;
        }

        // Lấy status từ Model. 
        // Giả sử model StockTransfer có hàm statuses() tương tự PartRequest.
        // Nếu không có, fallback về các hằng số đã định nghĩa.
        $statuses = method_exists(StockTransfer::class, 'statuses')
            ? StockTransfer::statuses()
            : [
                StockTransfer::STATUS_PENDING ?? 'PENDING',
                StockTransfer::STATUS_IN_TRANSIT ?? 'IN_TRANSIT',
                StockTransfer::STATUS_COMPLETED ?? 'COMPLETED'
            ];

        $totalRecords = 45;

        for ($i = 0; $i < $totalRecords; $i++) {
            // Logic luân chuyển: 85% từ Kho trung tâm -> Chi nhánh, 15% Chi nhánh -> Chi nhánh
            $isCentralToNormal = $faker->boolean(85);

            if ($isCentralToNormal) {
                $fromBranchId = $centralBranch->id;
                $toBranchId = $normalBranches->random()->id;
            } else {
                $fromBranchId = $normalBranches->random()->id;
                // Đảm bảo chi nhánh nhận khác chi nhánh gửi
                $toBranchId = $normalBranches->where('id', '!=', $fromBranchId)->random()->id ?? $centralBranch->id;
            }

            $status = $statuses[array_rand($statuses)];

            // Thời gian tạo phiếu ngẫu nhiên trong vòng 3 tháng qua
            $createdAt = $faker->dateTimeBetween('-90 days', 'now');

            // Tạo phiếu luân chuyển
            $transfer = StockTransfer::create([
                'from_branch_id' => $fromBranchId,
                'to_branch_id'   => $toBranchId,
                'status'         => $status,
                'created_by'     => $users->random(),
                'created_at'     => $createdAt,
                'updated_at'     => $createdAt,
            ]);

            // Thêm ngẫu nhiên từ 1 đến 6 loại phụ tùng cho mỗi phiếu luân chuyển
            $numParts = rand(1, 6);
            $selectedParts = $parts->random($numParts);

            $pivotData = [];
            foreach ($selectedParts as $part) {
                // Số lượng luân chuyển ngẫu nhiên nhưng thực tế (vd: 5, 10, 15, 20 cái)
                $pivotData[$part->id] = [
                    'quantity' => rand(1, 10) * 2,
                ];
            }

            // Dùng sync để lưu dữ liệu vào bảng trung gian (stock_transfer_details / pivot table)
            $transfer->parts()->sync($pivotData);
        }

        $this->command->info("Đã seed thành công {$totalRecords} Stock Transfers chuẩn production!");
    }
}
