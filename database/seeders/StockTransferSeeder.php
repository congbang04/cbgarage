<?php

namespace Database\Seeders;

use App\Models\StockTransfer;
use App\Models\Branch;
use App\Models\Part;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StockTransferSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $centralBranch = Branch::where('is_central_warehouse', true)->first();
        $normalBranches = Branch::where('is_central_warehouse', false)->get();
        $users = User::pluck('id');
        $parts = Part::all();

        if (!$centralBranch || $normalBranches->isEmpty() || $users->isEmpty() || $parts->isEmpty()) {
            $this->command->warn('Thiếu dữ liệu nền để chạy StockTransferSeeder.');
            return;
        }

        // Giảm xuống còn 15 record để dữ liệu trông cô đọng, dễ kiểm soát
        $totalRecords = 15;

        $realisticNotes = [
            'Điều chuyển định kỳ hàng tháng',
            'Bổ sung hàng tồn kho cho chi nhánh mới',
            'Hỗ trợ phụ tùng cho ca sửa chữa khẩn cấp',
            'Luân chuyển hàng hóa dư thừa theo yêu cầu quản lý',
            'Xuất kho linh kiện bảo trì định kỳ',
            'Cân đối tồn kho giữa các chi nhánh khu vực',
        ];

        for ($i = 0; $i < $totalRecords; $i++) {
            // Logic: Ưu tiên từ Kho tổng xuống các chi nhánh
            $isFromCentral = $faker->boolean(80);

            if ($isFromCentral) {
                $fromBranchId = $centralBranch->id;
                $toBranchId = $normalBranches->random()->id;
            } else {
                $fromBranchId = $normalBranches->random()->id;
                $toBranchId = $centralBranch->id; // Chuyển ngược về kho tổng (trả hàng)
            }

            // Phân bổ trạng thái: Đa số đã hoàn thành để xem được báo cáo, 1 ít đang đi đường
            $status = $faker->randomElement([
                StockTransfer::STATUS_COMPLETED,
                StockTransfer::STATUS_COMPLETED,
                StockTransfer::STATUS_IN_TRANSIT,
                StockTransfer::STATUS_PENDING
            ]);

            $createdAt = Carbon::instance($faker->dateTimeBetween('-30 days', 'now'));

            // Nếu COMPLETED thì updated_at phải sau created_at vài giờ/ngày
            $updatedAt = ($status === StockTransfer::STATUS_COMPLETED)
                ? (clone $createdAt)->addHours(rand(2, 48))
                : $createdAt;

            $transfer = StockTransfer::create([
                'from_branch_id' => $fromBranchId,
                'to_branch_id'   => $toBranchId,
                'status'         => $status,
                'note'           => $faker->randomElement($realisticNotes),
                'created_by'     => $users->random(),
                'created_at'     => $createdAt,
                'updated_at'     => $updatedAt,
            ]);

            // Mỗi phiếu chỉ nên có 2-4 loại phụ tùng để dashboard không bị quá dài
            $numParts = rand(2, 4);
            $selectedParts = $parts->random(min($numParts, $parts->count()));

            foreach ($selectedParts as $part) {
                $transfer->details()->create([
                    'part_id'  => $part->id,
                    'quantity' => rand(2, 10) * 5, // Số lượng chẵn (10, 15, 20...) trông sẽ chuyên nghiệp hơn
                ]);
            }
        }
    }
}
