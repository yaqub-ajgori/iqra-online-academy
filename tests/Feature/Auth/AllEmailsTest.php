<?php

use App\Models\User;
use App\Models\Student;
use App\Notifications\StudentVerifyEmail;
use App\Notifications\StudentResetPasswordNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;
use function Pest\Laravel\get;

beforeEach(function () {
    Notification::fake();
});

describe('Email Verification', function () {
    it('sends verification email when user registers', function () {
        Event::fake([Registered::class]);

        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ];

        post('/register', $userData);

        $user = User::where('email', 'test@example.com')->first();

        // Assert verification email was sent
        Notification::assertSentTo(
            $user,
            StudentVerifyEmail::class
        );

        expect($user)->toBeInstanceOf(\Illuminate\Contracts\Auth\MustVerifyEmail::class);
        expect($user->email_verified_at)->toBeNull();
    });

    it('resends verification email when requested', function () {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        Student::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        post('/email/verification-notification');

        Notification::assertSentTo(
            $user,
            StudentVerifyEmail::class
        );
    });

    it('uses custom StudentVerifyEmail notification', function () {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo(
            $user,
            StudentVerifyEmail::class,
            function ($notification) {
                // Verify it's the custom notification
                return $notification instanceof StudentVerifyEmail;
            }
        );
    });

    it('queues verification email notification', function () {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo(
            $user,
            StudentVerifyEmail::class,
            function ($notification) {
                // StudentVerifyEmail implements ShouldQueue
                return $notification instanceof \Illuminate\Contracts\Queue\ShouldQueue;
            }
        );
    });
});

describe('Password Reset Email', function () {
    it('sends password reset email for student users', function () {
        $user = User::factory()->create([
            'email' => 'student@example.com',
        ]);

        Student::factory()->create(['user_id' => $user->id]);

        // Assign student role
        $user->roles()->create([
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        post('/forgot-password', [
            'email' => 'student@example.com',
        ]);

        Notification::assertSentTo(
            $user,
            StudentResetPasswordNotification::class
        );
    });

    it('uses custom StudentResetPasswordNotification for students', function () {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);

        $user->roles()->create([
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $token = Password::createToken($user);
        $user->sendPasswordResetNotification($token);

        Notification::assertSentTo(
            $user,
            StudentResetPasswordNotification::class,
            function ($notification) use ($token) {
                return $notification instanceof StudentResetPasswordNotification;
            }
        );
    });

    it('queues password reset notification', function () {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);

        $user->roles()->create([
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $token = Password::createToken($user);
        $user->sendPasswordResetNotification($token);

        Notification::assertSentTo(
            $user,
            StudentResetPasswordNotification::class,
            function ($notification) {
                // StudentResetPasswordNotification implements ShouldQueue
                return $notification instanceof \Illuminate\Contracts\Queue\ShouldQueue;
            }
        );
    });

    it('sends password reset link successfully', function () {
        $user = User::factory()->create([
            'email' => 'reset@example.com',
        ]);

        Student::factory()->create(['user_id' => $user->id]);

        $user->roles()->create([
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $response = post('/forgot-password', [
            'email' => 'reset@example.com',
        ]);

        $response->assertSessionHas('status');

        Notification::assertSentTo($user, StudentResetPasswordNotification::class);
    });
});

describe('Email Templates', function () {
    it('verification email uses Bangla template', function () {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);

        $notification = new StudentVerifyEmail();
        $mailMessage = $notification->toMail($user);

        // Check that it uses the custom view
        expect($mailMessage->view)->toBe('emails.student_verify_email');
    });

    it('password reset email uses Bangla template', function () {
        $user = User::factory()->create();
        Student::factory()->create(['user_id' => $user->id]);

        $user->roles()->create([
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        $notification = new StudentResetPasswordNotification('test-token');
        $mailMessage = $notification->toMail($user);

        // Check that it uses the custom view
        expect($mailMessage->view)->toBe('emails.student_password_reset');
    });
});

describe('Complete Email Flows', function () {
    it('completes full registration with verification flow', function () {
        Event::fake([Registered::class]);

        // Register user
        $response = post('/register', [
            'name' => 'Complete Flow User',
            'email' => 'flow@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        // Assert user created
        assertDatabaseHas('users', ['email' => 'flow@example.com']);

        $user = User::where('email', 'flow@example.com')->first();

        // Assert student profile created
        assertDatabaseHas('students', ['user_id' => $user->id]);

        // Assert role assigned
        assertDatabaseHas('user_roles', [
            'user_id' => $user->id,
            'role_type' => 'student',
        ]);

        // Assert email sent
        Notification::assertSentTo($user, StudentVerifyEmail::class);

        // Assert redirected to verification notice
        $response->assertRedirect(route('verification.notice'));
    });

    it('completes full password reset flow', function () {
        $user = User::factory()->create(['email' => 'reset-flow@example.com']);
        Student::factory()->create(['user_id' => $user->id]);

        $user->roles()->create([
            'role_type' => 'student',
            'is_active' => true,
            'assigned_at' => now(),
        ]);

        // Request password reset
        $response = post('/forgot-password', [
            'email' => 'reset-flow@example.com',
        ]);

        // Assert notification sent
        Notification::assertSentTo($user, StudentResetPasswordNotification::class);

        // Assert response
        $response->assertSessionHas('status');
    });
});
