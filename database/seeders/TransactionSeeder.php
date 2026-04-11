<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    /**
     * Seed transactions data by branch
     * Tạo giao dịch thanh toán cho các đơn hàng COMPLETED, PAID
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        $paymentMethods = ['cash', 'bank_transfer', 'card', 'e_wallet'];

        // Lấy tất cả orders đã COMPLETED hoặc PAID
        $orders = Order::whereIn('status', ['COMPLETED', 'PAID'])->get();

        if ($orders->isEmpty()) {
            echo 'Không có Orders ở trạng thái COMPLETED hoặc PAID để seed transactions.' . PHP_EOL;
            return;
        }

        foreach ($orders as $order) {
            // 75% orders COMPLETED/PAID sẽ có transaction thanh toán
            if (rand(1, 100) <= 75) {
                // Payment transaction được tạo 1-5 ngày sau khi order completed
                $paymentDate = $order->updated_at->copy()->addDays(rand(1, 5));

                DB::table('transactions')->insert([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'type' => 'payment',
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_code' => $this->generateTransactionCode(),
                    'created_at' => $paymentDate,
                    'updated_at' => $paymentDate,
                ]);

                // 15% orders có refund
                if (rand(1, 100) <= 15) {
                    $refundAmount = $order->total_amount * (rand(10, 50) / 100);
                    $refundDate = $paymentDate->copy()->addDays(rand(2, 15));

                    DB::table('transactions')->insert([
                        'order_id' => $order->id,
                        'amount' => (int)$refundAmount,
                        'type' => 'refund',
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'transaction_code' => $this->generateTransactionCode(),
                        'created_at' => $refundDate,
                        'updated_at' => $refundDate,
                    ]);
                }
            }
        }
    }

    /**
     * Generate unique transaction code: TNX{YmdHis}{Random6}
     */
    protected function generateTransactionCode(): string
    {
        do {
            $code = 'TNX' . now()->format('YmdHis') . Str::random(6);
        } while (DB::table('transactions')->where('transaction_code', $code)->exists());

        return $code;
    }
}
