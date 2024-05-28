<?php

namespace Tests\Feature\Controller;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserController extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }



    // Esto sería para pasar en un header para usuarios autenticados.
    // $user = User::where('email', 'player@mail.com')->first();
    // $token = $user->createToken('loginToken')->accessToken;
}
