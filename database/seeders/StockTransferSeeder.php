<?php

namespace Database\Seeders;

use App\Models\StockTransfer;
use App\Models\Branch;
use App\Models\Part;
use App\Models\User;
use Illuminate\Database\Seeder;

class StockTransferSeeder extends Seeder
{
    public function run(): void
    {
        // Get central warehouse and branches
        $centralBranch = Branch::where('is_central_warehouse', true)->first();
        $branches = Branch::where('is_central_warehouse', false)->limit(2)->get();
        $creator = User::first();

        if (!$centralBranch || $branches->isEmpty() || !$creator) {
            $this->command->warn('Insufficient data for StockTransfer seeding.');
            return;
        }

        $parts = Part::limit(5)->get();

        if ($parts->isEmpty()) {
            $this->command->warn('No parts found for seeding.');
            return;
        }

        // Create sample stock transfers
        $transfers = [
            [
                'from_branch_id' => $centralBranch->id,
                'to_branch_id' => $branches[0]->id,
                'status' => 'pending',
                'created_by' => $creator->id,
                'created_at' => now(),
            ],
            [
                'from_branch_id' => $centralBranch->id,
                'to_branch_id' => $branches[1]->id,
                'status' => 'in_transit',
                'created_by' => $creator->id,
                'created_at' => now()->subDays(1),
            ],
            [
                'from_branch_id' => $centralBranch->id,
                'to_branch_id' => $branches[0]->id,
                'status' => 'completed',
                'created_by' => $creator->id,
                'created_at' => now()->subDays(3),
            ],
        ];

        foreach ($transfers as $transferData) {
            $transfer = StockTransfer::create($transferData);

            // Add parts with quantities
            $pivotData = [];
            foreach ($parts->random(2) as $part) {
                $pivotData[$part->id] = ['quantity' => rand(2, 10)];
            }

            $transfer->parts()->sync($pivotData);
        }

        $this->command->info('Stock Transfer data seeded successfully!');
    }
}
