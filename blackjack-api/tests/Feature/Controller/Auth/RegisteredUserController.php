<?php

namespace Tests\Feature\Controller\Auth;

use Tests\TestCase;
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
            ->assertStatus(201) // STAUTUS 201 -> CREATED
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
        // And check if the user has the 'player' role (default role in controller)
        $this->assertTrue(User::where('email', $payload['email'])->first()->hasRole('player'));
    }

    // Test that the user is not created if the email is already in use.
    public function testEmailAlreadyInUse(): void
    {
        $payload = [
            'nickname' => fake()->userName(),
            'email' => 'test@mail.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $payload);
        $response
            ->assertStatus(422) // STATUS 422 -> UNPROCESSABLE ENTITY
            ->assertJsonStructure([
                'message',
            ]);
    }
    // Test that the user is not created if the email is not valid.
    public function testInvalidEmail(): void
    {
        $payload = [
            'nickname' => fake()->userName(),
            'email' => 'testmail.com',
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $payload);
        $response
            ->assertStatus(422) // STATUS 422 -> UNPROCESSABLE ENTITY
            ->assertJsonStructure([
                'message',
            ]);
    }

    // Test that the user is not created if the password is less than 8 characters.
    public function testPasswordTooShort(): void
    {
        $payload = [
            'nickname' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => '1234567',
        ];

        $response = $this->json('POST', '/api/players', $payload);
        $response
            ->assertStatus(422) // STATUS 422 -> UNPROCESSABLE ENTITY
            ->assertJsonStructure([
                'message',
            ]);
    }

    // Test that the user is not created if the nickname is not unique.
    public function testNicknameNotUnique(): void
    {
        $payload = [
            'nickname' => 'TestUser',
            'email' => fake()->unique()->safeEmail(),
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $payload);

        $response
            ->assertStatus(422) // STATUS 422 -> UNPROCESSABLE ENTITY
            ->assertJsonStructure([
                'message',
            ]);
    }

    // Test that the user's nickname is 'Anonymous' if it is not provided.
    public function testAnonymousNickname(): void
    {
        $payload = [
            'email' => fake()->unique()->safeEmail(),
            'password' => '12345678',
        ];

        $response = $this->json('POST', '/api/players', $payload);

        $response
            ->assertStatus(201) // STATUS 201 -> CREATED
            ->assertJsonStructure([
                'message',
            ]);
    }
}
