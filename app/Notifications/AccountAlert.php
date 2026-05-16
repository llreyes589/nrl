<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AccountAlert extends Notification
{
    private string $title;
    private string $message;

    public function __construct(string $title, string $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    // Set WebPush as the delivery channel
    public function via($notifiable): array
    {
        return [WebPushChannel::class];
    }

    // Define the browser payload structure
    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($this->title)
            ->icon('/images/notification-icon.png')
            ->body($this->message)
            ->action('View Details', 'view_account')
            ->data(['id' => $notification->id, 'url' => '/home']);
    }
}
