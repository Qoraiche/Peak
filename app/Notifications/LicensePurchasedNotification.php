<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LicensePurchasedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
            ->subject('🎉 Your Peak Starter Kit License is Ready!')
            ->greeting('Hey ' . $notifiable->username . ' 👋')
            ->line('Thanks for your purchase! 🚀')
            ->line('Your license for the **Peak Admin & Starter Kit** is now active. 🎁')
            ->line('You can now access your downloads and get started right away.')
            ->action('🎯 Manage Your Licenses', url('/dashboard/licenses'))
            ->line('Need help? Our team is just a message away. 💬')
            ->salutation('Happy coding! 👨‍💻');
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
