<?php

namespace Tests\Feature\Controllers\Auth;

use Tests\TestCase;

class AuthenticatedUserControllerTest extends TestCase
{
    public function testSuccessfulLogin(): void
    {
        $payload = [
            'email' => 'test@mail.com',
            'password' => '123456789',
        ];

        $response = $this->json('POST', '/api/login', $payload);
        $response
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
            ]);
    }

    public function testUnsuccessfulLoginWithIncorrectPassword(): void
    {
        $payload = [
            'email' => 'test@mail.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/login', $payload);
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }

    public function testUnsuccessfulLoginWithNonExistentEmail(): void
    {
        $payload = [
            'email' => 'testing@mail.com',
            'password' => '123456789',
        ];

        $response = $this->json('POST', '/api/login', $payload);
        $response
            ->assertStatus(401)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
