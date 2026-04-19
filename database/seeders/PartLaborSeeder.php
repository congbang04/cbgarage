<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PartLaborSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Khai báo mảng map: 'Mã Phụ Tùng (SKU)' => ['Mã Tiền Công (SKU)']
        // Sử dụng mảng vì một phụ tùng có thể gọi nhiều công cùng lúc (tuy nhiên với DB hiện tại đa số là 1-1)
        $partLaborMap = [
            // FILTERS & OIL/FLUID (Bảo dưỡng cơ bản)
            'P-001' => ['LB-001'], // Lọc dầu động cơ -> Công thay dầu & lọc dầu
            'P-004' => ['LB-002'], // Lọc nhiên liệu -> Công thay lọc nhiên liệu
            'P-002' => ['LB-002'], // Lọc nhiên liệu -> Công thay lọc nhiên liệu
            'P-005' => ['LB-001'], // Dầu động cơ 5W30 -> Công thay dầu
            'P-006' => ['LB-001'], // Dầu động cơ 10W40 -> Công thay dầu
            'P-007' => ['LB-003'], // Dầu ATF -> Công thay dầu hộp số
            'P-008' => ['LB-003'], // Dầu hộp số sàn -> Công thay dầu hộp số
            'P-057' => ['LB-003'], // Lọc nhớt hộp số -> Công thay dầu hộp số
            'P-009' => ['LB-005'], // Nước làm mát -> Công thay nước làm mát
            'P-010' => ['LB-004'], // Dầu phanh -> Công xả e thay dầu phanh
            'P-011' => ['LB-006'], // DDVS kim phun -> Công vệ sinh kim phun

            // BRAKE (Hệ thống phanh)
            'P-012' => ['LB-007'], // Má phanh trước -> Công thay má phanh
            'P-013' => ['LB-007'], // Má phanh sau -> Công thay má phanh
            'P-014' => ['LB-008'], // Đĩa phanh trước -> Công thay đĩa phanh
            'P-015' => ['LB-008'], // Đĩa phanh sau -> Công thay đĩa phanh
            'P-016' => ['LB-009'], // Heo phanh -> Công thay cụm heo phanh

            // IGNITION (Hệ thống đánh lửa)
            'P-017' => ['LB-010'], // Bugi thường -> Công thay bugi
            'P-018' => ['LB-010'], // Bugi Iridium -> Công thay bugi
            'P-019' => ['LB-011'], // Cuộn đánh lửa -> Công thay cuộn đánh lửa (Bobin)

            // ELECTRICAL (Hệ thống điện & cảm biến)
            'P-028' => ['LB-012'], // Ắc quy 12V -> Công thay ắc quy
            'P-029' => ['LB-013'], // Máy phát điện -> Công tháo lắp máy phát
            'P-060' => ['LB-013'], // Máy phát điện -> Công tháo lắp máy phát
            'P-059' => ['LB-013'], // Máy phát điện -> Công tháo lắp máy phát
            'P-030' => ['LB-014'], // Máy đề -> Công tháo lắp thay củ đề
            'P-031' => ['LB-015'], // Cảm biến oxy -> Công thay cảm biến
            'P-032' => ['LB-015'], // Cảm biến ABS -> Công thay cảm biến
            'P-033' => ['LB-015'], // Cảm biến trục cam -> Công thay cảm biến
            'P-058' => ['LB-015'], // Cảm biến áp suất lốp -> Công thay cảm biến

            // BELT & SUSPENSION (Truyền động & Treo)
            'P-020' => ['LB-016'], // Dây curoa tổng -> Công thay dây curoa tổng
            'P-022' => ['LB-016'], // Puly tăng đai -> Công thay curoa tổng & puly
            'P-021' => ['LB-017'], // Dây curoa cam -> Công thay dây curoa cam
            'P-023' => ['LB-018'], // Giảm xóc trước -> Công thay giảm xóc
            'P-024' => ['LB-018'], // Giảm xóc sau -> Công thay giảm xóc
            'P-025' => ['LB-019'], // Rotuyn lái ngoài -> Công thay rotuyn
            'P-026' => ['LB-019'], // Rotuyn lái trong -> Công thay rotuyn
            'P-027' => ['LB-019'], // Thanh cân bằng -> Công thay rotuyn

            // AC (Điều hòa)
            'P-038' => ['LB-020'], // Lốc điều hòa -> Công thay lốc & nạp gas
            'P-040' => ['LB-020'], // Gas lạnh -> Công thay lốc & nạp gas
            'P-039' => ['LB-021'], // Quạt dàn nóng -> Công thay quạt dàn nóng
            'P-003' => ['LB-021'], // Quạt dàn nóng -> Công thay quạt dàn nóng

            // COOLING (Làm mát)
            'P-047' => ['LB-022'], // Két nước -> Công thay két nước
            'P-048' => ['LB-021'], // Quạt két nước -> Công thay quạt
            'P-049' => ['LB-022'], // Van hằng nhiệt -> Công thay két nước / van hằng nhiệt

            // TIRES & WIPER (Lốp & Gạt mưa)
            'P-041' => ['LB-023'], // Lốp 15 inch -> Công ra vào lốp
            'P-042' => ['LB-023'], // Lốp 16 inch -> Công ra vào lốp
            'P-043' => ['LB-023'], // Lốp 17 inch -> Công ra vào lốp
            'P-045' => ['LB-024'], // Cần gạt mưa -> Công thay gạt mưa
            'P-046' => ['LB-024'], // Motor gạt mưa -> Công thay gạt mưa

            // ENGINE MISC (Động cơ & Nhiên liệu)
            'P-050' => ['LB-025'], // Bơm xăng -> Công hạ bình xăng thay bơm
            'P-051' => ['LB-006'], // Kim phun xăng -> Công vệ sinh buồng đốt/kim phun
            'P-052' => ['LB-026'], // Gioăng quy lát -> Công thay ron nắp cò/quy lát
            'P-053' => ['LB-026'], // Gioăng nắp máy -> Công thay ron nắp cò/quy lát
        ];

        // Cache lại mảng ID của Parts và Labors để lấy cho nhanh, tránh gọi Query Builder quá nhiều trong vòng lặp
        $parts = DB::table('parts')->pluck('id', 'sku')->toArray();
        $labors = DB::table('labors')->pluck('id', 'sku')->toArray();

        // Mảng chứa data chuẩn bị insert
        $insertData = [];

        foreach ($partLaborMap as $partSku => $laborSkus) {
            // Kiểm tra xem part sku có tồn tại trong DB không
            if (isset($parts[$partSku])) {
                $partId = $parts[$partSku];

                foreach ($laborSkus as $laborSku) {
                    // Kiểm tra xem labor sku có tồn tại trong DB không
                    if (isset($labors[$laborSku])) {
                        $laborId = $labors[$laborSku];

                        $insertData[] = [
                            'part_id' => $partId,
                            'labor_id' => $laborId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Dùng insertOrIgnore để tránh bị lỗi duplicate key nếu chạy seeder nhiều lần
        if (!empty($insertData)) {
            DB::table('part_labor')->insertOrIgnore($insertData);
        }
    }
}
