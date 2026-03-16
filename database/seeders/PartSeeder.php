<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parts = [

            // FILTERS
            ['sku' => 'P-001', 'name' => 'Lọc dầu động cơ', 'price_sell' => 120000, 'unit' => 'cái'],
            ['sku' => 'P-002', 'name' => 'Lọc gió động cơ', 'price_sell' => 150000, 'unit' => 'cái'],
            ['sku' => 'P-003', 'name' => 'Lọc gió điều hòa', 'price_sell' => 180000, 'unit' => 'cái'],
            ['sku' => 'P-004', 'name' => 'Lọc nhiên liệu', 'price_sell' => 220000, 'unit' => 'cái'],

            // ENGINE OIL / FLUID
            ['sku' => 'P-005', 'name' => 'Dầu động cơ 5W30', 'price_sell' => 320000, 'unit' => 'lít'],
            ['sku' => 'P-006', 'name' => 'Dầu động cơ 10W40', 'price_sell' => 300000, 'unit' => 'lít'],
            ['sku' => 'P-007', 'name' => 'Dầu hộp số tự động ATF', 'price_sell' => 450000, 'unit' => 'lít'],
            ['sku' => 'P-008', 'name' => 'Dầu hộp số sàn', 'price_sell' => 280000, 'unit' => 'lít'],
            ['sku' => 'P-009', 'name' => 'Nước làm mát', 'price_sell' => 180000, 'unit' => 'chai'],
            ['sku' => 'P-010', 'name' => 'Dầu phanh DOT4', 'price_sell' => 150000, 'unit' => 'chai'],
            ['sku' => 'P-011', 'name' => 'Dung dịch vệ sinh kim phun', 'price_sell' => 200000, 'unit' => 'chai'],

            // BRAKE
            ['sku' => 'P-012', 'name' => 'Má phanh trước', 'price_sell' => 650000, 'unit' => 'bộ'],
            ['sku' => 'P-013', 'name' => 'Má phanh sau', 'price_sell' => 620000, 'unit' => 'bộ'],
            ['sku' => 'P-014', 'name' => 'Đĩa phanh trước', 'price_sell' => 1200000, 'unit' => 'cặp'],
            ['sku' => 'P-015', 'name' => 'Đĩa phanh sau', 'price_sell' => 1100000, 'unit' => 'cặp'],
            ['sku' => 'P-016', 'name' => 'Heo phanh', 'price_sell' => 950000, 'unit' => 'cái'],

            // IGNITION
            ['sku' => 'P-017', 'name' => 'Bugi thường', 'price_sell' => 90000, 'unit' => 'cái'],
            ['sku' => 'P-018', 'name' => 'Bugi Iridium', 'price_sell' => 220000, 'unit' => 'cái'],
            ['sku' => 'P-019', 'name' => 'Cuộn đánh lửa', 'price_sell' => 850000, 'unit' => 'cái'],

            // BELT
            ['sku' => 'P-020', 'name' => 'Dây curoa tổng', 'price_sell' => 350000, 'unit' => 'cái'],
            ['sku' => 'P-021', 'name' => 'Dây curoa cam', 'price_sell' => 850000, 'unit' => 'bộ'],
            ['sku' => 'P-022', 'name' => 'Puly tăng đai', 'price_sell' => 420000, 'unit' => 'cái'],

            // SUSPENSION
            ['sku' => 'P-023', 'name' => 'Giảm xóc trước', 'price_sell' => 1200000, 'unit' => 'cái'],
            ['sku' => 'P-024', 'name' => 'Giảm xóc sau', 'price_sell' => 1100000, 'unit' => 'cái'],
            ['sku' => 'P-025', 'name' => 'Rotuyn lái ngoài', 'price_sell' => 380000, 'unit' => 'cái'],
            ['sku' => 'P-026', 'name' => 'Rotuyn lái trong', 'price_sell' => 420000, 'unit' => 'cái'],
            ['sku' => 'P-027', 'name' => 'Thanh cân bằng', 'price_sell' => 450000, 'unit' => 'cái'],

            // ELECTRICAL
            ['sku' => 'P-028', 'name' => 'Ắc quy 12V', 'price_sell' => 1500000, 'unit' => 'cái'],
            ['sku' => 'P-029', 'name' => 'Máy phát điện', 'price_sell' => 3200000, 'unit' => 'cái'],
            ['sku' => 'P-030', 'name' => 'Máy đề', 'price_sell' => 2800000, 'unit' => 'cái'],
            ['sku' => 'P-031', 'name' => 'Cảm biến oxy', 'price_sell' => 950000, 'unit' => 'cái'],
            ['sku' => 'P-032', 'name' => 'Cảm biến ABS', 'price_sell' => 1200000, 'unit' => 'cái'],
            ['sku' => 'P-033', 'name' => 'Cảm biến trục cam', 'price_sell' => 750000, 'unit' => 'cái'],

            // LIGHTING
            ['sku' => 'P-034', 'name' => 'Đèn pha', 'price_sell' => 800000, 'unit' => 'cái'],
            ['sku' => 'P-035', 'name' => 'Đèn hậu', 'price_sell' => 700000, 'unit' => 'cái'],
            ['sku' => 'P-036', 'name' => 'Bóng đèn halogen', 'price_sell' => 90000, 'unit' => 'cái'],
            ['sku' => 'P-037', 'name' => 'Bóng đèn LED', 'price_sell' => 350000, 'unit' => 'cái'],

            // AC
            ['sku' => 'P-038', 'name' => 'Lốc điều hòa', 'price_sell' => 3500000, 'unit' => 'cái'],
            ['sku' => 'P-039', 'name' => 'Quạt dàn nóng', 'price_sell' => 850000, 'unit' => 'cái'],
            ['sku' => 'P-040', 'name' => 'Gas lạnh R134a', 'price_sell' => 450000, 'unit' => 'chai'],

            // TIRES
            ['sku' => 'P-041', 'name' => 'Lốp xe 15 inch', 'price_sell' => 1800000, 'unit' => 'cái'],
            ['sku' => 'P-042', 'name' => 'Lốp xe 16 inch', 'price_sell' => 2100000, 'unit' => 'cái'],
            ['sku' => 'P-043', 'name' => 'Lốp xe 17 inch', 'price_sell' => 2400000, 'unit' => 'cái'],
            ['sku' => 'P-044', 'name' => 'Van lốp', 'price_sell' => 50000, 'unit' => 'cái'],

            // WIPER
            ['sku' => 'P-045', 'name' => 'Cần gạt mưa', 'price_sell' => 150000, 'unit' => 'cái'],
            ['sku' => 'P-046', 'name' => 'Motor gạt mưa', 'price_sell' => 850000, 'unit' => 'cái'],

            // COOLING
            ['sku' => 'P-047', 'name' => 'Két nước', 'price_sell' => 1800000, 'unit' => 'cái'],
            ['sku' => 'P-048', 'name' => 'Quạt két nước', 'price_sell' => 950000, 'unit' => 'cái'],
            ['sku' => 'P-049', 'name' => 'Van hằng nhiệt', 'price_sell' => 220000, 'unit' => 'cái'],

            // FUEL
            ['sku' => 'P-050', 'name' => 'Bơm xăng', 'price_sell' => 1400000, 'unit' => 'cái'],
            ['sku' => 'P-051', 'name' => 'Kim phun xăng', 'price_sell' => 950000, 'unit' => 'cái'],

            // MISC
            ['sku' => 'P-052', 'name' => 'Gioăng quy lát', 'price_sell' => 1200000, 'unit' => 'bộ'],
            ['sku' => 'P-053', 'name' => 'Gioăng nắp máy', 'price_sell' => 380000, 'unit' => 'cái'],
            ['sku' => 'P-054', 'name' => 'Ống nước làm mát', 'price_sell' => 220000, 'unit' => 'cái'],
            ['sku' => 'P-055', 'name' => 'Ống dẫn nhiên liệu', 'price_sell' => 180000, 'unit' => 'cái'],
            ['sku' => 'P-056', 'name' => 'Ốc bánh xe', 'price_sell' => 25000, 'unit' => 'cái'],
            ['sku' => 'P-057', 'name' => 'Lọc nhớt hộp số', 'price_sell' => 350000, 'unit' => 'cái'],
            ['sku' => 'P-058', 'name' => 'Cảm biến áp suất lốp', 'price_sell' => 650000, 'unit' => 'cái'],
            ['sku' => 'P-059', 'name' => 'Dây mass', 'price_sell' => 90000, 'unit' => 'cái'],
            ['sku' => 'P-060', 'name' => 'Relay điện', 'price_sell' => 80000, 'unit' => 'cái'],
        ];

        foreach ($parts as $part) {
            DB::table('parts')->insert([
                'sku' => $part['sku'],
                'name' => $part['name'],
                'price_sell' => $part['price_sell'],
                'unit' => $part['unit'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
