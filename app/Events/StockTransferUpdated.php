<?php

namespace App\Events;

use App\Models\StockTransfer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StockTransferUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public StockTransfer $transfer) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('stock-transfers'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'StockTransferUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'transfer' => $this->transfer->load(['fromBranch', 'toBranch', 'creator']),
        ];
    }
}
