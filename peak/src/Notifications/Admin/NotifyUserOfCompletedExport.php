<?php

namespace Qoraiche\Peak\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Qoraiche\Peak\Models\Export;

class NotifyUserOfCompletedExport extends Notification
{
    use Queueable;

    /**
     * User
     *
     * @param Export $export
     */
    public function __construct(public Export $export, public $user)
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
            'type' => 'completed_export',
            'icon' => $this->user->profile_photo_link,
            'link' => $this->export->file_path,
            'body' => 'Your export is ready!',
            'user' => [
                'name' => $this->user->name
            ]
        ];
    }
}
