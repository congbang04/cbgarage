<?php

namespace App\Services;

use App\Models\Booking;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function confirmBooking(array $customerData): Booking
    {
        $bookingId = $customerData['booking_id'];
        return DB::transaction(function () use ($bookingId, $customerData) {
            // 1. Tìm và khóa chính xác bản ghi PENDING cần xác nhận
            $booking = Booking::where('status', 'PENDING')->lockForUpdate()->findOrFail($bookingId);

            // 2. Kiểm tra an toàn: Đảm bảo booking chưa hết hạn khóa
            if (Carbon::parse($booking->lock_expires_at)->isPast()) {
                DB::rollBack();
                $booking->delete(); // Xóa booking hết hạn này đi
                throw new Exception('Phiên đặt lịch của bạn đã hết hạn. Vui lòng thử lại.', 410); // 410 Gone
            }

            // 3. Cập nhật bản ghi với thông tin khách hàng và chuyển trạng thái
            $booking->update([
                'customer_name' => $customerData['customer_name'],
                'customer_phone' => $customerData['customer_phone'],
                'license_plate' => $customerData['license_plate'],
                'issue_description' => $customerData['issue_description'],
                'status' => 'CONFIRMED',
                'lock_expires_at' => null, // Xóa thời gian hết hạn
            ]);

            return $booking;
        });
    }
}
