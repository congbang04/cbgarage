<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Seed transactions data
     * Đảm bảo logic: Order PAID -> Giao dịch khớp tiền -> Xử lý hoàn tiền
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');
        $paymentMethods = ['CASH', 'BANK_TRANSFER', 'CARD', 'E_WALLET'];

        // 1. Chỉ lấy những orders có trạng thái PAID
        $orders = Order::where('status', Order::STATUS_PAID)->get();

        if ($orders->isEmpty()) {
            $this->command->info('Không có Orders ở trạng thái PAID để seed transactions.');
            return;
        }

        foreach ($orders as $order) {
            // Ngày thanh toán: 1-2 ngày sau khi order cập nhật cuối cùng
            $paymentDate = $order->updated_at->copy()->addHours(rand(1, 48));

            // Tỉ lệ 15% sẽ bị hoàn tiền (Refund)
            $isRefunded = (rand(1, 100) <= 15);

            // GIAO DỊCH 1: THANH TOÁN (Luôn có vì Order đang ở trạng thái PAID)
            DB::table('transactions')->insert([
                'order_id'       => $order->id,
                'amount'         => $order->total_amount, // Đảm bảo đúng số tiền trong Order
                'type'           => 'payment',
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'transaction_code' => $this->generateTransactionCode(),
                'created_at'     => $paymentDate,
                'updated_at'     => $paymentDate,
            ]);

            // XỬ LÝ NẾU CÓ HOÀN TIỀN
            if ($isRefunded) {
                // Ngày hoàn tiền: 1-3 ngày sau khi thanh toán
                $refundDate = $paymentDate->copy()->addDays(rand(1, 3));

                // GIAO DỊCH 2: HOÀN TIỀN (Thường hoàn 100% hoặc một phần, ở đây tôi để 100% theo logic trả trạng thái)
                DB::table('transactions')->insert([
                    'order_id'       => $order->id,
                    'amount'         => $order->total_amount,
                    'type'           => 'refund',
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_code' => $this->generateTransactionCode(),
                    'created_at'     => $refundDate,
                    'updated_at'     => $refundDate,
                ]);

                // CẬP NHẬT TRẠNG THÁI ORDER: Trả về COMPLETED
                $order->update([
                    'status'     => Order::STATUS_COMPLETED,
                    'updated_at' => $refundDate
                ]);
            }
        }
    }

    /**
     * Generate unique transaction code: TNX{YmdHis}{Random6}
     */
    protected function generateTransactionCode(): string
    {
        do {
            $code = 'TNX' . now()->format('YmdHis') . strtoupper(Str::random(6));
        } while (DB::table('transactions')->where('transaction_code', $code)->exists());

        return $code;
    }
}
