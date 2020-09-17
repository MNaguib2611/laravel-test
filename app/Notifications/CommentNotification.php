<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

//notify the userwhether his/her comment was approved or rejected
class CommentNotification extends Notification
{
    use Queueable;
    protected $notificationMessage; 
  
    public function __construct($notificationMessage)
    {
        $this->notificationMessage=$notificationMessage;
    }

    public function via($notifiable)
    {
        return ['database'];   //replaced mail with database 
    }

    public function toArray($notifiable)
    {
        return [
            'data' => $this->notificationMessage
        ];
    }
}
