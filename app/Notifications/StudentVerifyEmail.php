<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class StudentVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    // You can customize the notification here if needed (subject, mail view, etc.)

    protected function buildMailMessage($url)
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('ইমেইল যাচাই করুন | ' . config('app.name'))
            ->view('emails.student_verify_email', [
                'url' => $url,
                'appName' => config('app.name'),
            ]);
    }

    public function toMail($notifiable)
    {
        return $this->buildMailMessage($this->verificationUrl($notifiable));
    }
} 