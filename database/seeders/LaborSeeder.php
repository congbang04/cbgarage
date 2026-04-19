<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labors = [
            // ==========================================
            // CÔNG BẢO DƯỠNG CƠ BẢN (FILTERS & OIL/FLUID)
            // ==========================================
            ['sku' => 'LB-001', 'name' => 'Công thay dầu và lọc dầu động cơ', 'price' => 50000, 'description' => 'Xả nhớt cũ, thay lọc nhớt và châm nhớt mới'],
            ['sku' => 'LB-002', 'name' => 'Công thay lọc nhiên liệu', 'price' => 150000, 'description' => 'Tháo lắp và thay thế lọc xăng/dầu tinh'],
            ['sku' => 'LB-003', 'name' => 'Công thay dầu hộp số (Tuần hoàn máy)', 'price' => 300000, 'description' => 'Sử dụng máy thông rửa và thay dầu hộp số tự động'],
            ['sku' => 'LB-004', 'name' => 'Công xả e và thay dầu phanh', 'price' => 200000, 'description' => 'Xả gió hệ thống phanh 4 bánh và châm dầu DOT4'],
            ['sku' => 'LB-005', 'name' => 'Công súc rửa và thay nước làm mát', 'price' => 150000, 'description' => 'Xả E, súc rửa két nước và nạp dung dịch mới'],
            ['sku' => 'LB-006', 'name' => 'Công vệ sinh buồng đốt / kim phun', 'price' => 250000, 'description' => 'Súc béc bằng dung dịch chuyên dụng'],

            // ==========================================
            // CÔNG HỆ THỐNG PHANH (BRAKE)
            // ==========================================
            ['sku' => 'LB-007', 'name' => 'Công thay má phanh trước/sau (2 bánh)', 'price' => 150000, 'description' => 'Tháo lắp lốp, thay cụm má phanh, bôi trơn ắc phanh'],
            ['sku' => 'LB-008', 'name' => 'Công thay đĩa phanh', 'price' => 200000, 'description' => 'Tháo cụm heo phanh, thay đĩa phanh mới'],
            ['sku' => 'LB-009', 'name' => 'Công thay cụm heo phanh (Caliber)', 'price' => 250000, 'description' => 'Thay thế heo phanh và xả gió hệ thống'],

            // ==========================================
            // CÔNG HỆ THỐNG ĐÁNH LỬA & ĐIỆN (IGNITION & ELECTRICAL)
            // ==========================================
            ['sku' => 'LB-010', 'name' => 'Công thay bugi (4 cái)', 'price' => 100000, 'description' => 'Tháo bobin, thay thế bugi mới'],
            ['sku' => 'LB-011', 'name' => 'Công thay cuộn đánh lửa (Bobin)', 'price' => 50000, 'description' => 'Tháo lắp bobin đơn'],
            ['sku' => 'LB-012', 'name' => 'Công thay bình ắc quy', 'price' => 50000, 'description' => 'Tháo lắp, vệ sinh cọc bình và cài đặt lại (nếu có)'],
            ['sku' => 'LB-013', 'name' => 'Công tháo lắp và thay máy phát điện', 'price' => 400000, 'description' => 'Tháo dây curoa tổng, thay cụm máy phát'],
            ['sku' => 'LB-014', 'name' => 'Công tháo lắp thay củ đề', 'price' => 350000, 'description' => 'Thay thế củ đề khởi động'],
            ['sku' => 'LB-015', 'name' => 'Công thay các loại cảm biến', 'price' => 150000, 'description' => 'Thay cảm biến ABS, Oxy, Trục cam... và xóa lỗi ODB'],

            // ==========================================
            // CÔNG HỆ THỐNG TRUYỀN ĐỘNG & TREO (BELT & SUSPENSION)
            // ==========================================
            ['sku' => 'LB-016', 'name' => 'Công thay dây curoa tổng & puly', 'price' => 200000, 'description' => 'Tháo lắp và căng đai curoa máy'],
            ['sku' => 'LB-017', 'name' => 'Công thay dây curoa cam (Timing Belt)', 'price' => 800000, 'description' => 'Hạ mặt máy, canh lại dấu cam và thay đai'],
            ['sku' => 'LB-018', 'name' => 'Công thay giảm xóc (1 cây)', 'price' => 250000, 'description' => 'Tháo lắp phuộc nhún, thay thế lò xo/bát bèo'],
            ['sku' => 'LB-019', 'name' => 'Công thay cụm rotuyn (Lái/Cân bằng)', 'price' => 150000, 'description' => 'Cảo rotuyn cũ, ép rotuyn mới'],

            // ==========================================
            // CÔNG HỆ THỐNG ĐIỀU HÒA (AC) & LÀM MÁT (COOLING)
            // ==========================================
            ['sku' => 'LB-020', 'name' => 'Công thay lốc điều hòa & nạp gas', 'price' => 600000, 'description' => 'Tháo lốc lạnh, hút chân không, nạp gas và dầu lạnh mới'],
            ['sku' => 'LB-021', 'name' => 'Công thay quạt dàn nóng / két nước', 'price' => 250000, 'description' => 'Tháo lắp cụm quạt giải nhiệt'],
            ['sku' => 'LB-022', 'name' => 'Công tháo lắp và thay két nước', 'price' => 450000, 'description' => 'Tháo két nước thủng, lắp két mới và châm nước giải nhiệt'],

            // ==========================================
            // CÔNG LỐP (TIRES) & KHÁC
            // ==========================================
            ['sku' => 'LB-023', 'name' => 'Công ra vào lốp & cân mâm (1 quả)', 'price' => 70000, 'description' => 'Sử dụng máy cạy lốp và cân bằng động bấm chì'],
            ['sku' => 'LB-024', 'name' => 'Công thay cụm gạt mưa', 'price' => 30000, 'description' => 'Tháo lắp cần/chổi gạt mưa'],
            ['sku' => 'LB-025', 'name' => 'Công hạ bình xăng thay cụm bơm', 'price' => 500000, 'description' => 'Hạ bình chứa nhiên liệu, thay bơm xăng và lọc thô'],
            ['sku' => 'LB-026', 'name' => 'Công thay ron nắp cò/quy lát', 'price' => 800000, 'description' => 'Tháo mặt máy, cạo ron cũ, dán keo và ép ron mới'],
        ];

        foreach ($labors as $labor) {
            DB::table('labors')->insert([
                'sku' => $labor['sku'],
                'name' => $labor['name'],
                'price' => $labor['price'],
                'description' => $labor['description'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
