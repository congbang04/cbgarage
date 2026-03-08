<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingLock;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BookingService
{
    public function createBooking($branchId, $serviceId, $customerId, $startTime, $lockId): Booking
    {
        return DB::transaction(function () use ($branchId, $serviceId, $customerId, $startTime, $lockId) {
            // 1. Tìm và khóa chính xác bản ghi PENDING cần xác nhận
            $booking = Booking::where('status', 'PENDING')->lockForUpdate()->findOrFail($bookingId);

            $lock = BookingLock::find($lockId);

            if (!$lock || $lock->expires_at->isPast()) {
                DB::rollBack();

                if ($lock) {
                    $lock->delete();
                }

                throw new Exception('Phiên đặt lịch của bạn đã hết hạn. Vui lòng thử lại.', 410);
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
