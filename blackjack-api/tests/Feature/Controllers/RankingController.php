<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;

class RankingController extends TestCase
{
    // FUNCTION TO TEST: ranking
    public function testRanking(): void
    {
        $response = $this->json('GET', '/api/players/ranking');

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'message',
                'ranking' => [
                    '*' => [
                        'nickname',
                        'gameStats',
                    ]
                ],
            ]);
    }

    // FUNCTION TO TEST: bestPlayer
    public function testBestPlayer(): void
    {
        $response = $this->json('GET', '/api/players/ranking/winner');

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'message',
                'user_nickname',
                'user_stats',
            ]);
    }

    // FUNCTION TO TEST: worstPlayer
    public function testWorstPlayer(): void
    {
        $response = $this->json('GET', '/api/players/ranking/loser');

        $response
            ->assertStatus(200) // STATUS 200 -> OK
            ->assertJsonStructure([
                'message',
                'user_nickname',
                'user_stats',
            ]);
    }
}
