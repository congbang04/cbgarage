<?php

namespace App\Events;

use App\Models\PartRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class PartRequestUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public PartRequest $partRequest)
    {
        $this->partRequest = $partRequest;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('part-requests'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'PartRequestUpdated';
    }

    public function broadcastWith(): array
    {
        return [
            'partRequest' => $this->partRequest->load(['technician', 'order']),
        ];
    }
}
