<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusNotification extends Notification
{
    use Queueable;
    protected $notificationMessage; 


    public function __construct(String $notificationMessage)
    {
       $this->notificationMessage=$notificationMessage;
    }


    public function via($notifiable)
    {
        return ['database'];
    }

        public function toArray($notifiable)
    {
        return [
            'data' => $this->notificationMessage
        ];
    }
}
