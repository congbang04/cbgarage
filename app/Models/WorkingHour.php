<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkingHour extends Model
{
    use HasFactory;

    // Chỉ định khóa chính là 'day_of_week' và không tự tăng
    protected $primaryKey = 'day_of_week';
    public $incrementing = false;

    protected $fillable = [
        'day_of_week',
        'active',
        'start_time',
        'end_time',
        'lunch_start',
        'lunch_end',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Lấy tên ngày trong tuần (Thứ 2, Thứ 3...)
     * get{Tên thuộc tính viết hoa}Attribute → tạo ra một biến ảo (attribute) có tên snake_case tương ứng.
     * Ví dụ: getDayNameAttribute → $model->day_name
     */
    public function getDayNameAttribute(): string
    {
        return [
            0 => 'Chủ nhật',
            1 => 'Thứ 2',
            2 => 'Thứ 3',
            3 => 'Thứ 4',
            4 => 'Thứ 5',
            5 => 'Thứ 6',
            6 => 'Thứ 7',
        ][$this->day_of_week] ?? 'Không xác định';
    }
    public static function getByDayOfWeek($dayOfWeek)
    {
        return self::find($dayOfWeek);
    }
}
