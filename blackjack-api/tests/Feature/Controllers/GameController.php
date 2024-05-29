<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;

class GameController extends TestCase
{
    // FUNCTION TO TEST: store
    public function testStore(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/players/' . $user->uuid . '/games');

        $response
            ->assertStatus(201) // STATUS 201 -> CREATED
            ->assertJsonStructure([
                'message',
                'game_details',
                'result',
            ])
            ->assertJson([
                'game_details' => $response['game_details'],
                'result' => $response['result'],
            ]);

        // Check if a game was created for the user.
        $this->assertDatabaseHas('games', [
            'user_uuid' => $user->uuid,
        ]);
    }

    // Test to check if the user is not authenticated.
    public function testStoreUnauthenticated(): void
    {
        $response = $this->json('POST', '/api/players/1/games');

        $response
            ->assertStatus(401) // STATUS 401 -> UNAUTHORIZED
            ->assertJsonStructure([
                'message',
            ]);
    }

    // Test to check if the user doesn't have the necessary role (player).
    public function testStoreUnauthorized(): void
    {
        // Login as a moderator.
        $moderator = User::where('email', 'moderator@mail.com')->first();
        $token = $moderator->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/players/' . $moderator->uuid . '/games');

        $response
            ->assertStatus(403) // STATUS 403 -> FORBIDDEN
            ->assertJsonStructure([
                'message',
            ]);
    }

    // Test to check if authenticated user has a different uuid than the one in the URL.
    public function testStoreDifferentUuid(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        // Get moderator's uuid.
        $moderator = User::where('email', 'moderator@mail.com')->first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('POST', '/api/players/' . $moderator->uuid . '/games');

        $response
            ->assertStatus(403) // STATUS 403 -> FORBIDDEN
            ->assertJsonStructure([
                'message',
            ]);
    }

    // FUNCTION TO TEST: show    
    public function testShow(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players/' . $user->uuid . '/games');

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'user_nickname',
                'game_stats',
                'games' => [
                    '*' => [
                        'player_hand',
                        'dealer_hand',
                        'player_score',
                        'dealer_score',
                        'result',
                    ]
                ],
            ])
            ->assertJson([
                'user_nickname' => $user->nickname,
                'game_stats' => $response['game_stats'],
                'games' => $response['games'],
            ]);
    }

    // This test checks if the user owner of the games doesn't exist.
    public function testShowWithInvalidUserId(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players/invalid-user-id/games');

        $response->assertStatus(404); // STATUS 404 -> NOT FOUND
    }

    // This test checks if the user has not the necessary role (player).
    public function testShowWithUnauthorizedUser(): void
    {
        // Login as a moderator.
        $moderator = User::where('email', 'moderator@mail.com')->first();
        $token = $moderator->createToken('loginToken')->accessToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players/' . $moderator->uuid . '/games');

        $response->assertStatus(403); // STATUS 403 -> FORBIDDEN
    }

    // This test checks if the uuid of the user doesn't match the authenticated user.
    public function testShowWithDifferentUuid(): void
    {
        // Login as a player.
        $user = User::where('email', 'test@mail.com')->first();
        $token = $user->createToken('loginToken')->accessToken;

        $moderator = User::where('email', 'moderator@mail.com')->first();

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players/' . $moderator->uuid . '/games');

        $response->assertStatus(403); // STATUS 403 -> FORBIDDEN
    }

    // FUNCTION TO TEST: destroyAll
}
