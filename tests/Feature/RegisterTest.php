<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Patient;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_patient()
    {
        $payload = [
            'fullName' => 'Test Person',
            'dob' => '1990-01-01',
            'gender' => 'Male',
            'address' => '123 Test St',
            'contact' => '09171234567',
            'email' => 'test@example.com',
            'exposureDate' => '2025-10-01',
            'exposureType' => 'Bite',
            'animal' => 'Dog',
            'vaccinationStatus' => 'First time (no doses yet)',
            'lastDoseDate' => null,
            'clinic' => 'Test Clinic',
            'emergencyContact' => 'Mom 09170000000',
        ];

        $response = $this->postJson('/register', $payload);

        $response->assertStatus(201);

        $this->assertDatabaseHas('patients', [
            'name' => 'Test Person',
            'email' => 'test@example.com',
            'animal' => 'Dog',
            'exposure_type' => 'Bite',
            'clinic' => 'Test Clinic',
        ]);
    }
}
