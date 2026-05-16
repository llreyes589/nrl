<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class AppActionAlert extends Notification
{
    use Queueable;


    private string $title;
    private string $message;
    private string $route;
    
    /**
     * __construct
     *
     * @param  mixed $title
     * @param  mixed $message
     * @param  mixed $route
     * @return void
     */
    public function __construct(?string $title, ?string $message, ?string $route)
    {
        $this->title = $title;
        $this->message = $message;
        $this->route = $route;
    }

      public function via($notifiable)
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
            ->action('View Receipt', 'view_receipt')
            ->data(['id' => $notification->id, 'url' => $this->route]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
