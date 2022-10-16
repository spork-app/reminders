<?php

namespace Spork\Reminders\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spork\Core\Models\FeatureList;

class ReminderTriggered implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $reminder;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(FeatureList $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|\Illuminate\Broadcasting\Channel[]
     */
    public function broadcastOn()
    {
        return new PrivateChannel('reminder');
    }

    public function broadcastAs()
    {
        return 'user.'.$this->reminder->user_id;
    }
}
