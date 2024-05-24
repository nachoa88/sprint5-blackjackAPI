<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $id)
    {
        // Find the user.
        $user = User::findOrFail($id);

        // Create a new game with the user's UUID.
        $game = Game::factory()->create([
            'user_uuid' => $user->uuid,
            'deck_id' => '1',
        ]);

        // Shuffle deck before starting the game.
        $game->shuffleDeck();

        // Deal two cards to the player and the dealer.
        $playerHand = $game->dealCards(2);
        $dealerHand = $game->dealCards(2);

        // Calculate the scores.
        $playerScore = $game->calculateScore($playerHand);
        $dealerScore = $game->calculateScore($dealerHand);

        // Determine the result of the game.
        $result = $game->determineResult($playerScore, $dealerScore);

        // Update the game with the hands, scores, and result.
        $game->update([
            'player_hand' => $playerHand,
            'dealer_hand' => $dealerHand,
            'player_score' => $playerScore,
            'dealer_score' => $dealerScore,
            'result' => $result,
        ]);

        return response()->json([
            'message' => 'Game created successfully',
            'game details' => $game,
            'status' => 201
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        //
    }
}
