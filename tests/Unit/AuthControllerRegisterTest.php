<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'job_seeker',

        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(200);
        $response->assertJsonStructure(['result', 'message', 'status']);
        $response->assertJson(['status' => true, 'message' => 'Registered Successfully']);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
    }

    public function test_invalid_registration_data()
    {
        // Provide invalid registration data
        $invalidUserData = [
            'name' => 'John Doe',
            'email' => 'invalid-email', // Invalid email
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'employer',
        ];

        $response = $this->postJson('/api/register', $invalidUserData);

        $response->assertStatus(200);
        $response->assertJsonStructure(['error', 'message', 'status']);
        $response->assertJson(['status' => 0, 'error' => 'validation_error']);
    }
}
