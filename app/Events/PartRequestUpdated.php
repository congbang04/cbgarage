<?php

namespace App\Events;

use App\Models\PartRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PartRequestUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public PartRequest $partRequest) {}

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
