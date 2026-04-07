<?php

namespace App\Services;

use App\Models\Booking;
use Exception;
use Illuminate\Support\Carbon;

class CustomerBookingService
{
    /**
     * Lấy danh sách booking của user hiện tại với phân trang
     *
     * @param Carbon|null $startDate - Filter theo ngày đặt
     * @param string|null $status - Filter theo trạng thái
     * @param int $perPage - Số item trên mỗi trang
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getCustomerBookings(?Carbon $startDate = null, ?string $status = null, int $perPage = 10)
    {
        $userId = auth()->user()->customer->id ?? null;

        if (!$userId) {
            return collect([]);
        }

        $query = Booking::where('customer_id', $userId)
            ->with(['customer', 'car', 'service', 'bay']);

        // Filter theo ngày đặt
        if ($startDate) {
            $query->whereDate('start_time', $startDate);
        }

        // Filter theo trạng thái
        if (!empty($status) && in_array($status, Booking::statuses())) {
            $query->where('status', $status);
        }

        return $query->orderByDesc('created_at')
            ->paginate($perPage)
            ->through(fn($booking) => $this->mapBookingData($booking));
    }

    /**
     * Hủy lịch đặt
     * Chỉ cho phép hủy nếu created_at <= 24 giờ
     *
     * @param int $id - ID của booking
     * @throws Exception
     */
    public function cancel(int $id)
    {
        $booking = Booking::findOrFail($id);

        // Kiểm tra quyền sở hữu
        if ($booking->customer_id !== auth()->user()->customer->id) {
            throw new Exception('Bạn không có quyền hủy lịch này', 403);
        }

        // Kiểm tra thời gian hủy (trong 24 giờ)
        if (now()->gt($booking->created_at->addHours(24))) {
            throw new Exception('Quá hạn hủy lịch (chỉ được hủy trong 24 giờ)', 400);
        }

        // Kiểm tra trạng thái
        if ($booking->status === Booking::STATUS_CANCELED) {
            throw new Exception('Lịch này đã bị hủy rồi', 400);
        }

        $booking->update(['status' => Booking::STATUS_CANCELED]);
    }

    /**
     * Map dữ liệu booking với thông tin bổ sung
     */
    private function mapBookingData(Booking $booking): array
    {
        return [
            'id' => $booking->id,
            'code' => str_pad($booking->id, 4, '0', STR_PAD_LEFT),
            'customer_name' => $booking->contact_name ??  $booking->customer->name,
            'customer_phone' => $booking->contact_phone ?? $booking->customer->phone,
            'car_plate' => $booking->car?->license_plate ?? '-',
            'start_time' => Carbon::parse($booking->start_time)->format('d/m/Y H:i'),
            'end_time' => Carbon::parse($booking->end_time)->format('d/m/Y H:i'),
            'status' => $booking->status,
            'status_name' => $this->getStatusName($booking->status),
            'can_cancel' => now()->lt($booking->created_at->addHours(1)) &&
                $booking->status !== Booking::STATUS_CANCELED &&
                $booking->status !== Booking::STATUS_CONFIRMED,
            'created_at' => $booking->created_at,
        ];
    }

    /**
     * Lấy tên trạng thái theo language
     */
    private function getStatusName(string $status): string
    {
        return match ($status) {
            Booking::STATUS_PENDING => 'Đã đặt lịch',
            Booking::STATUS_CONFIRMED => 'Đã tiếp nhận',
            Booking::STATUS_CANCELED => 'Đã hủy',
            default => 'Không xác định',
        };
    }
}
