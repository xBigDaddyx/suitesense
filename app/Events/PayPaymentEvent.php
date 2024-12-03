<?php

namespace App\Events;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PayPaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public Payment $record;
    public User $user;
    public $amount;
    public $payment_method;
    /**
     * Create a new event instance.
     */
    public function __construct(Payment $record, User $user, $amount)
    {
        $this->record = $record;
        $this->user = $user;
        $this->amount = $amount['paid_amount'];
        $this->payment_method = $amount['payment_method'];
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
