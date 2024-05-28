<?php

namespace Tests\Feature\Controller\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends TestCase
{
    public function testRegister(): void
    {
        // Create a fake user and try to register it.
        $payload = [
            'nickname' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/players', $payload);
        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'message',
            ]);

        // Verify that the user was created in the database
        $this->assertDatabaseHas('users', [
            'nickname' => $payload['nickname'],
            'email' => $payload['email'],
            'wins' => 0,
            'losses' => 0,
            'ties' => 0,
        ]);
        // Check the password separately because the the result of Hash::check is a boolean.
        $this->assertTrue(Hash::check($payload['password'], User::first()->password));
    }

    // Tests to-do:
    // 1. Test that the user is not created if the email is already in use.
    // 2. Test that the user is not created if the email is not valid.
    // 3. Test that the user is not created if the password is less than 8 characters.
    // 4. Test that the user is not created if the nickname is not unique.
    // 5. Test that the user's nickname is 'Anonymous' if it is not provided.
    // 6. Test that the user is created with the default role 'player'.
}
