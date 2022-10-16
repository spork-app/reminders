<?php

namespace App\Features\Reminders\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spork\Reminders\Events\ReminderTriggered;
use Spork\Reminders\Notifications\ReminderTriggeredNotification;

class SendReminderTriggeredNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReminderTriggered $event)
    {
        $reminder = $event->reminder;
        $reminder->load('user');

        $reminder->user->notify(new ReminderTriggeredNotification($reminder));
    }
}
