<?php

namespace Tests\Feature\Controller\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticatedUserController extends TestCase
{
    public function testLogin(): void
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

    // Esto sería para pasar en un header para usuarios autenticados.
    // $user = User::where('email', 'player@mail.com')->first();
    // $token = $user->createToken('loginToken')->accessToken;
}
