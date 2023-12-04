<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class QueuedVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable)
    {
        // You can customize the email here
        return (new MailMessage)
            ->from('artyombenikyan@mail.ru', 'Artyom')
            ->subject('Custom Email Verification')
            ->line('The introduction to the notification.')
            ->action('Verify Email Address', $this->verificationUrl($notifiable));
    }
}







