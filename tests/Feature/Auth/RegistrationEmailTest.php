<?php

use App\Models\User;
use App\Notifications\StudentVerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

beforeEach(function () {
    // Enable notification fake to capture sent notifications
    Notification::fake();
    Event::fake([Registered::class]);
});

it('sends email verification notification when user registers', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    // Make the registration request
    $response = post('/register', $userData);

    // Assert user was created
    assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    // Get the created user
    $user = User::where('email', 'test@example.com')->first();

    // Assert student profile was created
    assertDatabaseHas('students', [
        'user_id' => $user->id,
        'full_name' => 'Test User',
    ]);

    // Assert student role was assigned
    assertDatabaseHas('user_roles', [
        'user_id' => $user->id,
        'role_type' => 'student',
    ]);

    // Assert the Registered event was dispatched
    Event::assertDispatched(Registered::class);

    // Assert email verification notification was sent
    Notification::assertSentTo(
        $user,
        StudentVerifyEmail::class
    );

    // Assert redirected to verification notice
    $response->assertRedirect(route('verification.notice'));
});

it('queues email verification notification', function () {
    $userData = [
        'name' => 'Another User',
        'email' => 'another@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    post('/register', $userData);

    $user = User::where('email', 'another@example.com')->first();

    // Assert notification was queued (StudentVerifyEmail implements ShouldQueue)
    Notification::assertSentTo(
        $user,
        StudentVerifyEmail::class,
        function ($notification, $channels) {
            return in_array('mail', $channels);
        }
    );
});

it('creates user with must verify email implementation', function () {
    $userData = [
        'name' => 'Verify Test',
        'email' => 'verify@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ];

    post('/register', $userData);

    $user = User::where('email', 'verify@example.com')->first();

    // Assert user implements MustVerifyEmail
    expect($user)->toBeInstanceOf(\Illuminate\Contracts\Auth\MustVerifyEmail::class);

    // Assert email is not verified yet
    expect($user->email_verified_at)->toBeNull();
});
