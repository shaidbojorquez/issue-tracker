<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing user signup.
     *
     * @return void
     */
    public function testCanRegisterUser()
    {
        // When
        $response = $this->json('POST', '/api/auth/signup', [
            'name' => 'Test user',
            'email' => 'test@prueba.com',
            'password' => 'admin123',
            'password_confirmation' => 'admin123',
        ]);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);
    }
}
