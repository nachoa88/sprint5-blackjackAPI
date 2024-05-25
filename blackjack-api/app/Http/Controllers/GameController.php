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

        // Deal two cards to the player and the dealer & Use the helper function to get only details neede of the cards in a hand.
        $playerHand = $game->getHandDetails($game->dealCards(2));
        $dealerHand = $game->getHandDetails($game->dealCards(2));

        // Calculate the scores.
        $playerScore = $game->calculateScore($playerHand);
        $dealerScore = $game->calculateScore($dealerHand);

        // Determine the result of the game.
        $result = $game->determineResult($playerScore, $dealerScore);

        $user->game_result = $result;
        // Save the user with the updated game result.
        $user->save();

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
            'player_cards' => $game->player_hand,
            'dealer_cards' => $game->dealer_hand,
            'player_score' => $game->player_score,
            'dealer_score' => $game->dealer_score,
            'result' => $game->result,
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
