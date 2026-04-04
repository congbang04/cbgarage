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
        $paymentMethods = ['cash', 'bank_transfer', 'card'];

        // Get all COMPLETED and PAID orders grouped by branch
        $orders = Order::whereIn('status', ['COMPLETED', 'PAID'])
            ->with('branch')
            ->get();

        foreach ($orders as $order) {
            // 50% chance to have payment transaction
            if ($order->status === 'PAID' || rand(1, 100) <= 50) {
                // Create payment transaction
                $createdAt = now()->subDays(rand(0, 30));

                DB::table('transactions')->insert([
                    'order_id' => $order->id,
                    'amount' => $order->total_amount,
                    'type' => 'payment',
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'transaction_code' => $this->generateTransactionCode(),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 20% chance for refund
                if (rand(1, 100) <= 20) {
                    $refundAmount = $order->total_amount * (rand(20, 100) / 100);

                    DB::table('transactions')->insert([
                        'order_id' => $order->id,
                        'amount' => (int)$refundAmount,
                        'type' => 'refund',
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'transaction_code' => $this->generateTransactionCode(),
                        'created_at' => $createdAt->addDays(rand(1, 10)),
                        'updated_at' => $createdAt->addDays(rand(1, 10)),
                    ]);
                }
            }
        }

        $this->command->info('✅ TransactionSeeder: ' . DB::table('transactions')->count() . ' transactions created.');
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
