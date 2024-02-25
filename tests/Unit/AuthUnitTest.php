<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;

class AuthUnitTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_successful()
    {
        // Create a user for testing
        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['result', 'status', 'message']);
        $response->assertJson(['status' => true, 'message' => 'Logged In Successfully']);
    }

    public function test_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'invalid_password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message']);
        $response->assertJson(['status' => false, 'message' => 'Invalid login details']);
    }



}
