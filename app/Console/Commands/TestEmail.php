<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\StudentVerifyEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'email:test {email? : Email address to send test to}';

    /**
     * The console command description.
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Testing email configuration...');
        $this->newLine();

        // Get email address
        $email = $this->argument('email') ?? $this->ask('Enter email address to send test to');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address!');
            return self::FAILURE;
        }

        try {
            // Test 1: Simple mail
            $this->info('Test 1: Sending simple test email...');
            Mail::raw('This is a test email from Iqra Online Academy. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                    ->subject('Test Email - Iqra Online Academy');
            });
            $this->info('✓ Simple test email sent successfully!');
            $this->newLine();

            // Test 2: Check if there's a user with this email
            $user = User::where('email', $email)->first();

            if ($user) {
                $this->info('Test 2: Sending verification email to existing user...');
                $user->sendEmailVerificationNotification();
                $this->info('✓ Verification email queued for user: ' . $user->name);
                $this->warn('⚠ Remember to run: php artisan queue:work');
            } else {
                $this->warn('No user found with email: ' . $email);
                $this->info('Skipping verification email test.');
            }

            $this->newLine();
            $this->info('✓ Email test completed!');
            $this->newLine();

            $this->table(
                ['Configuration', 'Value'],
                [
                    ['MAIL_MAILER', config('mail.default')],
                    ['MAIL_HOST', config('mail.mailers.smtp.host')],
                    ['MAIL_PORT', config('mail.mailers.smtp.port')],
                    ['MAIL_FROM_ADDRESS', config('mail.from.address')],
                    ['MAIL_FROM_NAME', config('mail.from.name')],
                    ['QUEUE_CONNECTION', config('queue.default')],
                ]
            );

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Please check your email configuration in .env file');
            return self::FAILURE;
        }
    }
}
