<?php

namespace Tests\Feature\Controller;

use Tests\TestCase;
use App\Models\User;

class GameController extends TestCase
{
     // FUNCTION TO TEST: show
    //  public function testShow(): void
    //  {
    //      // Login as a player.
    //      $user = User::where('email', 'test@mail.com')->first();
    //      $token = $user->createToken('loginToken')->accessToken;
 
    //      $response = $this->withHeader('Authorization', 'Bearer ' . $token)->json('GET', '/api/players/' . $user->id . '/games');
 
    //      $response
    //          ->assertStatus(200) // STATUS 200 -> OK
    //          ->assertJsonStructure([
    //              'user_nickname',
    //              'game_stats',
    //              'games',
    //          ]);
    //  }
}
