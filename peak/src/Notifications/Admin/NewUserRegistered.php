<?php

namespace Qoraiche\Peak\Notifications\Admin;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification
{
    use Queueable, NotificationContentTrait;

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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
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
            'icon' => '/storage/users/default.png',
            'body' => 'This is an example, when the user clicks this notification it will go to the link.',
            'link' => '/dashboard',
            'user' => [
                'name' => 'John Doe'
            ]
        ];
    }

    /**
     * @throws Exception
     */
    public function toDatabase($notifiable)
    {
        // Fetch notification content from the database template
        $content = $this->getNotificationContent('new_user_registered', [
            'user' => $notifiable
        ]);

        return new DatabaseMessage([
            'title' => $content['title'],
            'body' => $content['body']
        ]);
    }

    /**
     * Get the notification's database type.
     *
     * @param object $notifiable
     * @return string
     */
    public function databaseType(object $notifiable): string
    {
        return 'admin.new-user-registered';
    }
}
