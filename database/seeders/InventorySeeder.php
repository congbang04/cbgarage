<?php

namespace Database\Seeders;

use App\Models\Inventory;
use App\Models\Part;
use App\Models\Branch;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // Get central warehouse
        $centralBranch = Branch::where('is_central_warehouse', true)->first();

        if (!$centralBranch) {
            // Create central warehouse if not exists
            $centralBranch = Branch::create([
                'name' => 'Kho Tổng (Khu công nghiệp Tân Bình)',
                'address' => 'Blockchain St, District 2, Ho Chi Minh',
                'is_central_warehouse' => true,
                'phone' => '(028) 9999-8888',
                'is_actived' => true,
            ]);
        }

        // Mapping của SKU với quantity và min_stock
        $inventoryData = [
            // FILTERS
            'P-001' => ['quantity' => 25, 'min_stock' => 8],      // Lọc dầu động cơ
            'P-002' => ['quantity' => 20, 'min_stock' => 7],      // Lọc gió động cơ
            'P-003' => ['quantity' => 18, 'min_stock' => 6],      // Lọc gió điều hòa
            'P-004' => ['quantity' => 15, 'min_stock' => 5],      // Lọc nhiên liệu

            // ENGINE OIL / FLUID
            'P-005' => ['quantity' => 40, 'min_stock' => 12],     // Dầu động cơ 5W30
            'P-006' => ['quantity' => 38, 'min_stock' => 12],     // Dầu động cơ 10W40
            'P-007' => ['quantity' => 22, 'min_stock' => 8],      // Dầu hộp số tự động ATF
            'P-008' => ['quantity' => 28, 'min_stock' => 10],     // Dầu hộp số sàn
            'P-009' => ['quantity' => 35, 'min_stock' => 10],     // Nước làm mát
            'P-010' => ['quantity' => 18, 'min_stock' => 7],      // Dầu phanh DOT4
            'P-011' => ['quantity' => 24, 'min_stock' => 8],      // Dung dịch vệ sinh kim phun

            // BRAKE
            'P-012' => ['quantity' => 12, 'min_stock' => 4],      // Má phanh trước
            'P-013' => ['quantity' => 12, 'min_stock' => 4],      // Má phanh sau
            'P-014' => ['quantity' => 8, 'min_stock' => 2],       // Đĩa phanh trước
            'P-015' => ['quantity' => 8, 'min_stock' => 2],       // Đĩa phanh sau
            'P-016' => ['quantity' => 6, 'min_stock' => 2],       // Heo phanh

            // IGNITION
            'P-017' => ['quantity' => 40, 'min_stock' => 15],     // Bugi thường
            'P-018' => ['quantity' => 20, 'min_stock' => 8],      // Bugi Iridium
            'P-019' => ['quantity' => 10, 'min_stock' => 3],      // Cuộn đánh lửa

            // BELT
            'P-020' => ['quantity' => 12, 'min_stock' => 4],      // Dây curoa tổng
            'P-021' => ['quantity' => 8, 'min_stock' => 3],       // Dây curoa cam
            'P-022' => ['quantity' => 10, 'min_stock' => 3],      // Puly tăng đai

            // SUSPENSION
            'P-023' => ['quantity' => 8, 'min_stock' => 2],       // Giảm xóc trước
            'P-024' => ['quantity' => 8, 'min_stock' => 2],       // Giảm xóc sau
            'P-025' => ['quantity' => 15, 'min_stock' => 5],      // Rotuyn lái ngoài
            'P-026' => ['quantity' => 14, 'min_stock' => 5],      // Rotuyn lái trong
            'P-027' => ['quantity' => 12, 'min_stock' => 4],      // Thanh cân bằng

            // ELECTRICAL
            'P-028' => ['quantity' => 6, 'min_stock' => 2],       // Ắc quy 12V
            'P-029' => ['quantity' => 4, 'min_stock' => 1],       // Máy phát điện
            'P-030' => ['quantity' => 4, 'min_stock' => 1],       // Máy đề
            'P-031' => ['quantity' => 10, 'min_stock' => 3],      // Cảm biến oxy
            'P-032' => ['quantity' => 8, 'min_stock' => 2],       // Cảm biến ABS
            'P-033' => ['quantity' => 12, 'min_stock' => 4],      // Cảm biến trục cam

            // LIGHTING
            'P-034' => ['quantity' => 10, 'min_stock' => 3],      // Đèn pha
            'P-035' => ['quantity' => 10, 'min_stock' => 3],      // Đèn hậu
            'P-036' => ['quantity' => 45, 'min_stock' => 15],     // Bóng đèn halogen
            'P-037' => ['quantity' => 25, 'min_stock' => 8],      // Bóng đèn LED

            // AC
            'P-038' => ['quantity' => 3, 'min_stock' => 1],       // Lốc điều hòa
            'P-039' => ['quantity' => 6, 'min_stock' => 2],       // Quạt dàn nóng
            'P-040' => ['quantity' => 15, 'min_stock' => 5],      // Gas lạnh R134a

            // TIRES
            'P-041' => ['quantity' => 16, 'min_stock' => 4],      // Lốp xe 15 inch
            'P-042' => ['quantity' => 14, 'min_stock' => 4],      // Lốp xe 16 inch
            'P-043' => ['quantity' => 12, 'min_stock' => 3],      // Lốp xe 17 inch
            'P-044' => ['quantity' => 80, 'min_stock' => 20],     // Van lốp

            // WIPER
            'P-045' => ['quantity' => 30, 'min_stock' => 10],     // Cần gạt mưa
            'P-046' => ['quantity' => 8, 'min_stock' => 2],       // Motor gạt mưa

            // COOLING
            'P-047' => ['quantity' => 6, 'min_stock' => 2],       // Két nước
            'P-048' => ['quantity' => 8, 'min_stock' => 2],       // Quạt két nước
            'P-049' => ['quantity' => 18, 'min_stock' => 6],      // Van hằng nhiệt

            // FUEL
            'P-050' => ['quantity' => 8, 'min_stock' => 2],       // Bơm xăng
            'P-051' => ['quantity' => 10, 'min_stock' => 3],      // Kim phun xăng

            // MISC
            'P-052' => ['quantity' => 8, 'min_stock' => 2],       // Gioăng quy lát
            'P-053' => ['quantity' => 12, 'min_stock' => 4],      // Gioăng nắp máy
            'P-054' => ['quantity' => 14, 'min_stock' => 5],      // Ống nước làm mát
            'P-055' => ['quantity' => 16, 'min_stock' => 5],      // Ống dẫn nhiên liệu
            'P-056' => ['quantity' => 200, 'min_stock' => 50],    // Ốc bánh xe
            'P-057' => ['quantity' => 16, 'min_stock' => 5],      // Lọc nhớt hộp số
            'P-058' => ['quantity' => 10, 'min_stock' => 3],      // Cảm biến áp suất lốp
            'P-059' => ['quantity' => 30, 'min_stock' => 10],     // Dây mass
            'P-060' => ['quantity' => 50, 'min_stock' => 15],     // Relay điện
        ];

        // Get all parts from PartSeeder
        $parts = Part::all();

        foreach ($parts as $part) {
            // Get inventory data from mapping, or use defaults
            if (isset($inventoryData[$part->sku])) {
                $data = $inventoryData[$part->sku];
            } else {
                // Default values for any additional parts
                $data = [
                    'quantity' => rand(5, 30),
                    'min_stock' => rand(2, 8),
                ];
            }

            // Create or update inventory
            Inventory::updateOrCreate(
                [
                    'part_id' => $part->id,
                    'branch_id' => $centralBranch->id,
                ],
                [
                    'quantity' => $data['quantity'],
                    'min_stock' => $data['min_stock'],
                ]
            );
        }

        $this->command->info('✅ Inventory seeded for ' . $parts->count() . ' parts!');
    }
}
