<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequestForPasswordReset extends Notification implements ShouldQueue
{
    use Queueable;

    protected $url;
    protected $name;
    protected $email;

    /**
     * Create a new notification instance.
     */
    public function __construct($url, $name, $email)
    {
        $this->url = $url;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hello!, we are from '.env('APP_NAME'))
            ->line('You or someone requesting password reset for account with name '.$this->name.' and email address '.$this->email.', ignore this email if you are not requesting it')
            ->action('Reset my password', $this->url)
            ->line('Thank you for using our application!');
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
