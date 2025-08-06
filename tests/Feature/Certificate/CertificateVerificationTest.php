<?php

use App\Models\{User, Student, Teacher, Course, Certificate};
use App\Services\CertificateService;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    $this->certificateService = app(CertificateService::class);
});

test('certificate can be verified with valid code', function () {
    $certificate = Certificate::factory()->create([
        'is_verified' => true,
        'is_revoked' => false,
        'expiry_date' => null,
    ]);

    $verified = $this->certificateService->verifyCertificate($certificate->verification_code);

    expect($verified)->toBeInstanceOf(Certificate::class);
    expect($verified->id)->toBe($certificate->id);
});

test('certificate verification fails for invalid code', function () {
    $verified = $this->certificateService->verifyCertificate('INVALID12345');

    expect($verified)->toBeNull();
});

test('certificate verification fails for revoked certificate', function () {
    $certificate = Certificate::factory()->revoked()->create();

    $verified = $this->certificateService->verifyCertificate($certificate->verification_code);

    expect($verified)->toBeNull();
});

test('certificate verification fails for expired certificate', function () {
    $certificate = Certificate::factory()->create([
        'expiry_date' => now()->subDay(),
        'is_verified' => true,
        'is_revoked' => false,
    ]);

    $verified = $this->certificateService->verifyCertificate($certificate->verification_code);

    expect($verified)->toBeNull();
});

test('certificate verification works for non-expired certificate', function () {
    $certificate = Certificate::factory()->create([
        'expiry_date' => now()->addYear(),
        'is_verified' => true,
        'is_revoked' => false,
    ]);

    $verified = $this->certificateService->verifyCertificate($certificate->verification_code);

    expect($verified)->toBeInstanceOf(Certificate::class);
});

test('certificate status is correct for different states', function () {
    // Valid certificate
    $valid = Certificate::factory()->create([
        'is_verified' => true,
        'is_revoked' => false,
        'expiry_date' => null,
    ]);
    expect($valid->status)->toBe('valid');

    // Revoked certificate
    $revoked = Certificate::factory()->revoked()->create();
    expect($revoked->status)->toBe('revoked');

    // Unverified certificate
    $unverified = Certificate::factory()->create([
        'is_verified' => false,
        'is_revoked' => false,
    ]);
    expect($unverified->status)->toBe('unverified');

    // Expired certificate
    $expired = Certificate::factory()->create([
        'is_verified' => true,
        'is_revoked' => false,
        'expiry_date' => now()->subDay(),
    ]);
    expect($expired->status)->toBe('expired');
});

test('certificate can be revoked and restored', function () {
    $certificate = Certificate::factory()->create([
        'is_verified' => true,
        'is_revoked' => false,
    ]);

    // Revoke certificate
    $certificate->revoke('Administrative decision');
    $certificate->refresh();

    expect($certificate->is_revoked)->toBeTrue();
    expect($certificate->revoked_at)->not->toBeNull();
    expect($certificate->revocation_reason)->toBe('Administrative decision');

    // Restore certificate
    $certificate->restore();
    $certificate->refresh();

    expect($certificate->is_revoked)->toBeFalse();
    expect($certificate->revoked_at)->toBeNull();
    expect($certificate->revocation_reason)->toBeNull();
});

test('certificate number generation is unique', function () {
    $cert1 = Certificate::factory()->create();
    $cert2 = Certificate::factory()->create();

    expect($cert1->certificate_number)->not->toBe($cert2->certificate_number);
    expect($cert1->certificate_number)->toStartWith('IOA-');
    expect($cert2->certificate_number)->toStartWith('IOA-');
});

test('verification code generation is unique', function () {
    $cert1 = Certificate::factory()->create();
    $cert2 = Certificate::factory()->create();

    expect($cert1->verification_code)->not->toBe($cert2->verification_code);
    expect($cert1->verification_code)->toHaveLength(12);
    expect($cert2->verification_code)->toHaveLength(12);
});