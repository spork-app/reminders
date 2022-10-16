<?php

namespace Spork\Reminders\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;
use Spork\Core\Models\FeatureList;

class ReminderTriggeredNotification extends Notification
{
    use Queueable;

    protected $reminder;

    public function __construct(FeatureList $reminder)
    {
        $this->reminder = $reminder;
    }

    public function via($notifiable)
    {
        return ['mail', 'broadcast', 'database'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->reminder->toArray());
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line(sprintf('Remember! %s', $this->reminder->name))
                    ->action('View notification', url('/reminders'))
                    ->line('It occurs at: '.Arr::first($this->reminder->next_twelve_occurrences)->format('F j, Y H:i a'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->reminder->toArray();
    }
}
