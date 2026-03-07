<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\DayOff;
use App\Models\WorkingHour;

class WorkingTimeService
{
    /**
     * Cập nhật đồng loạt 7 ngày trong tuần
     */
    public function updateWeeklyHours(array $hoursData): void
    {
        $upsertData = [];
        // foreach ($array as $key => $value) {}
        foreach ($hoursData as $dayOfWeek => $data) {
            $upsertData[] = [
                'day_of_week' => (int) $dayOfWeek,
                'active' => (bool) ($data['active'] ?? false),
                'start_time' => $data['start_time'] ?? null,
                'end_time' => $data['end_time'] ?? null,
                'lunch_start' => $data['lunch_start'] ?? null,
                'lunch_end' => $data['lunch_end'] ?? null,
            ];
        }

        // Sử dụng upsert để cập nhật hoặc tạo mới hàng loạt
        WorkingHour::upsert($upsertData, ['day_of_week'], [
            'active',
            'start_time',
            'end_time',
            'lunch_start',
            'lunch_end'
        ]);
    }

    public function addDayOff(string $date, ?string $reason): DayOff
    {
        return DayOff::create([
            'date' => $date,
            'reason' => $reason,
        ]);
    }

    public function deleteDayOff(DayOff $dayOff): void
    {
        $dayOff->delete();
    }

    // Lấy giờ làm việc, bao gồm cả giờ nghỉ trưa, cho một ngày cụ thể.
    // @return array|null Trả về array (start, end, lunch) hoặc null nếu nghỉ.
    public function getWorkingHoursForDate(Carbon $date): array|null
    {
        // B1: Check ngày nghỉ có lưu trong CSDL
        if (DayOff::where('date', $date->toDateString())->exists()) {
            return null;
        }

        $dayOfWeek = $date->dayOfWeek; // Thứ trong tuần (0 = Chủ Nhật, 1 = Thứ Hai, ..., 6 = Thứ Bảy)

        // B2: Lấy cấu hình từ DB
        $dbConfig = WorkingHour::getByDayOfWeek($dayOfWeek);

        // B3: Xử lý kết quả
        if ($dbConfig) {
            // Nếu có cấu hình DB
            if (!$dbConfig->active) {
                return null;
            }

            $startTime = $date->copy()->setTimeFromTimeString($dbConfig->start_time);
            $endTime = $date->copy()->setTimeFromTimeString($dbConfig->end_time);

            $lunch = null;
            if ($dbConfig->lunch_start && $dbConfig->lunch_end) {
                $lunch = [
                    'start' => $date->copy()->setTimeFromTimeString($dbConfig->lunch_start),
                    'end' => $date->copy()->setTimeFromTimeString($dbConfig->lunch_end),
                ];
            }
            return ['start' => $startTime, 'end' => $endTime, 'lunch' => $lunch];
        } else {
            // B4: Fallback về logic mặc định (nếu DB rỗng)
            return $this->getDefaultWorkingHours($date);
        }
    }

    /**
     * Logic mặc định (fallback) - Dùng lại hàm cũ logic cứng
     */
    private function getDefaultWorkingHours(Carbon $date): ?array
    {
        if ($date->isSunday()) {
            return null;
        }

        $startTime = $date->copy()->setTime(8, 0); // 08:00

        if ($date->isSaturday()) {
            $endTime = $date->copy()->setTime(12, 0); // 12:00
        } else { // Thứ 2 -> Thứ 6
            $endTime = $date->copy()->setTime(20, 0); // 20:00
        }

        return ['start' => $startTime, 'end' => $endTime, 'lunch' => null];
    }
}
