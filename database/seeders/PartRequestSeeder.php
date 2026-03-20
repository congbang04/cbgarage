<?php

namespace Database\Seeders;

use App\Models\PartRequest;
use App\Models\PartRequestDetail;
use App\Models\Order;
use App\Models\Technician;
use App\Models\Part;
use Illuminate\Database\Seeder;

class PartRequestSeeder extends Seeder
{
    public function run(): void
    {
        // Get sample data
        $orders = Order::limit(3)->get();
        $technicians = Technician::limit(2)->get();
        $parts = Part::limit(4)->get();

        if ($orders->isEmpty() || $technicians->isEmpty() || $parts->isEmpty()) {
            $this->command->warn('Insufficient data for PartRequest seeding. Run other seeders first.');
            return;
        }

        // Create sample part requests
        $requests = [
            [
                'order_id' => $orders[0]->id,
                'technician_id' => $technicians[0]->id,
                'status' => 'pending',
                'note' => 'Thay thế bộ trọc đầu',
                'created_at' => now()->subHours(2),
            ],
            [
                'order_id' => $orders[1]->id,
                'technician_id' => $technicians[1]->id,
                'status' => 'pending',
                'note' => 'Thay lốp và bảo dưỡng định kỳ',
                'created_at' => now()->subHours(1),
            ],
            [
                'order_id' => $orders[2]->id,
                'technician_id' => $technicians[0]->id,
                'status' => 'completed',
                'note' => 'Sửa lọc gió',
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($requests as $index => $requestData) {
            $partRequest = PartRequest::create($requestData);

            // Add details
            $selectedParts = $parts->random(min(2, $parts->count()));
            foreach ($selectedParts as $part) {
                PartRequestDetail::create([
                    'request_id' => $partRequest->id,
                    'part_id' => $part->id,
                    'quantity' => rand(1, 5),
                ]);
            }
        }

        $this->command->info('Part Request data seeded successfully!');
    }
}
