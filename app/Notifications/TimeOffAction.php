<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimeOffAction extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $timeOffAction;

    public function __construct($timeOffAction)
    {
        $this->timeOffAction = $timeOffAction;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
         return ['mail', 'database']; // Emails employee and logs in DB
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Time-off request ' . $this->timeOffAction['action'])
            ->greeting('Hi ' . $notifiable->name)
            ->line('Your time-off request has been ' . $this->timeOffAction['action'])
           ->salutation('Thanks');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
