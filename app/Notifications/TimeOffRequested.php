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
            ->line($this->timeOffRequest['user'] . ' has submitted a new time-off request from ' . $this->timeOffRequest['from'] . ' to ' . $this->timeOffRequest['to'])
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
            'user' => $this->timeOffRequest['user'],
            'message' => 'New time off request from ' . $this->timeOffRequest['from']
        ];
    }
}
