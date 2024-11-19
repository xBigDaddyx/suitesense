<?php

namespace App\Events;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestCheckinEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $data;
    public Reservation $record;
    public User $user;

    /**
     * Create a new event instance.
     */
    public function __construct(array $data, Reservation $record, User $user)
    {
        $this->data = $data;
        $this->record = $record;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
