<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\User;

class UserController extends TestCase
{
    // FUNCTION TO TEST: getAll
    // Test if the authenticated user can get all players.
    public function testGetAll(): void
    {
        // Login as a moderator.
        $user = User::where('email', 'moderator@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        // Send the header with the token to the endpoint to get all players.
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players');

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'total_wins_average',
                'total_losses_average',
                'total_ties_average',
                'user_details',
            ]);
    }

    // Test if the authenticated user doesn't have permission to get all players.
    public function testGetAllWithoutPermission(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players');

        $response
            ->assertStatus(403); // STATUS 403 -> FORBIDDEN
    }

    // Test if the user is not authenticated to get all players.
    public function testGetAllWithoutAuthentication(): void
    {
        // Send the request to the endpoint to get all players without the header.
        $response = $this->json('GET', '/api/players');

        $response
            ->assertStatus(401); // STATUS 401 -> UNAUTHORIZED
    }

    // FUNCTION TO TEST: update
    // Test if the authenticated user can update the nickname of a player.
    public function testUpdate(): void
    {
        // Login as a moderator.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        // Send the header with the token to the endpoint to update the nickname of a player.
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/players/' . $user->uuid, [
            'nickname' => 'NewNickname',
        ]);

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'message',
                'new nickname',
            ]);

        // Check if the nickname was updated correctly.
        $this->assertDatabaseHas('users', [
            'uuid' => $user->uuid,
            'nickname' => 'NewNickname',
        ]);
    }

    // Test if the authenticated user doesn't have permission to update the nickname of a player.
    public function testUpdateWithoutPermission(): void
    {
        // Login as a player.
        $user = User::where('email', 'moderator@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/players/' . $user->uuid, [
            'nickname' => 'NewNickname',
        ]);

        $response
            ->assertStatus(403); // STATUS 403 -> FORBIDDEN
    }

    // Test if the user is not authenticated to update the nickname of a player.
    public function testUpdateWithoutAuthentication(): void
    {
        // Send the request to the endpoint to update the nickname of a player without the header.
        $response = $this->json('PUT', '/api/players/1', [
            'nickname' => 'NewNickname',
        ]);

        $response
            ->assertStatus(401); // STATUS 401 -> UNAUTHORIZED
    }

    // Test if authenticated user's uuid is not the same as the one in the URL.
    public function testUpdateWithDifferentUuid(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        // Get also the uuid of the moderator to pass it to the endpoint.
        $moderator = User::where('email', 'moderator@mail.com')->first();

        // Send the header with the token to the endpoint to update the nickname of the moderator logged as a player.
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/players/' . $moderator->uuid, [
            'nickname' => 'NewNickname',
        ]);

        $response
            ->assertStatus(403); // STATUS 403 -> FORBIDDEN
    }

    // Test null nickname update and if it is set to 'Anonymous'.
    public function testUpdateWithNullNickname(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/players/' . $user->uuid, [
            'nickname' => null,
        ]);

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'message',
                'new nickname',
            ]);

        // Check if the nickname was updated correctly to 'Anonymous'.
        $this->assertDatabaseHas('users', [
            'uuid' => $user->uuid,
            'nickname' => 'Anonymous',
        ]);
    }

    // Test updating the nickname of a player with a nickname that already exists.
    public function testUpdateWithExistingNickname(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        // Create a new user with the same nickname.
        $newUser = User::factory()->create([
            'nickname' => 'NewNickname',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('PUT', '/api/players/' . $user->uuid, [
            'nickname' => 'NewNickname',
        ]);

        $response
            ->assertStatus(422) // STATUS 422 -> UNPROCESSABLE ENTITY
            ->assertJsonValidationErrors([
                'nickname',
            ]);
    }
}
