<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class GameController extends Controller
{
    // Create a new game for the player
    public function store(Request $request, $id): JsonResponse
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
        ], 201);
    }

    // Show info of one player
    public function show($id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::findOrFail($id);

        Gate::authorize('view', $user);

        // Get the games of the user.
        $user->load('games');

        // Prepare the games data. (This maybe could be done with a Resource to prepare Game Data)
        $gamesData = $user->games->map(function ($game) {
            return [
                'player_hand' => json_decode($game->player_hand),
                'dealer_hand' => json_decode($game->dealer_hand),
                'player_score' => $game->player_score,
                'dealer_score' => $game->dealer_score,
                'result' => $game->result,
            ];
        });

        // Return the info of the games of the user.
        return response()->json([
            'user_nickname' => $user->nickname,
            'game_stats' => $user->game_stats,
            'games' => $gamesData
        ], 200);
    }

    // Delete all game history for player
    public function destroyAll($id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::findOrFail($id);

        // Check if the authenticated user can delete the user's games, and has roles & permissions.
        Gate::authorize('deleteAllGames', $user);

        // Delete the user's games.
        $user->games()->delete();

        // Reset user stats for wins, losses and ties.
        $user->wins = 0;
        $user->losses = 0;
        $user->ties = 0;
        $user->save();

        return response()->json(['message' => 'All games deleted successfully'], 200);
    }
}
