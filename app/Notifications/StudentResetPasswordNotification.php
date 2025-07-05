<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class StudentResetPasswordNotification extends ResetPassword implements ShouldQueue
{
    /**
     * The name of the queue connection to use when queuing the notification.
     *
     * @var string|null
     */
    public $connection = null;

    /**
     * The name of the queue the notification should be sent to.
     *
     * @var string|null
     */
    public $queue = null;

    /**
     * The delay (in seconds) before the queued notification is processed.
     *
     * @var int|null
     */
    public $delay = null;

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('পাসওয়ার্ড রিসেট অনুরোধ | ' . config('app.name'))
            ->view('emails.student_password_reset', [
                'resetUrl' => $resetUrl,
                'count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire'),
            ]);
    }
} 