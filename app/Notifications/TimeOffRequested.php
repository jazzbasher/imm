<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TimeOffRequested extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $timeOffRequest;

    public function __construct($timeOffRequest)
    {
        $this->timeOffRequest = $timeOffRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // Emails manager and logs in DB
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('A new time-off request has been submitted.')
            ->action('Review Request', url('/manager/requests'))
            ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->timeOffRequest['id'],
            'message' => 'New time off request from ' . $this->timeOffRequest['total']
        ];
    }
}
