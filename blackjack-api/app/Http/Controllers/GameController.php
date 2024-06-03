<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use App\Services\GameService;

class GameController extends Controller
{
    /**
     * @OA\Post(
     *     path="/players/{id}/games",
     *     tags={"Games"},
     *     summary="Create a new game for a player",
     *     description="This endpoint creates a new game for a player by their UUID. Only authenticated players can access this endpoint.",
     *     operationId="createGame",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the player to create a game for",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Game created successfully"),
     *             @OA\Property(
     *                 property="game_details",
     *                 type="object",
     *                 @OA\Property(property="player_cards", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="dealer_cards", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="player_score", type="integer"),
     *                 @OA\Property(property="dealer_score", type="integer"),
     *             ),
     *             @OA\Property(property="result", type="string", example="win"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player not found"),
     *         )
     *     ),
     * )
     */
    // Create a new game for the player
    public function store(Request $request, $id): JsonResponse
    {
        // Find the user.
        $user = User::findOrFail($id);

        Gate::authorize('playGames', $user);

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
            'game_details' => [
                'player_cards' => $game->player_hand,
                'dealer_cards' => $game->dealer_hand,
                'player_score' => $game->player_score,
                'dealer_score' => $game->dealer_score,
            ],
            'result' => $game->result,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/players/{id}/games",
     *     tags={"Games"},
     *     summary="Show game info of a player",
     *     description="This endpoint returns the game info of a player by their UUID. Only authenticated users can access this endpoint.",
     *     operationId="showGame",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the player to retrieve game info for",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="player_hand", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="dealer_hand", type="array", @OA\Items(type="string")),
     *                 @OA\Property(property="player_score", type="integer"),
     *                 @OA\Property(property="dealer_score", type="integer"),
     *                 @OA\Property(property="result", type="string", example="win"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player not found"),
     *         )
     *     ),
     * )
     */
    // Show game info of one player
    public function show($id, GameService $gameService): JsonResponse
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

        $userStats = $gameService->getGameStats($user);

        // Return the info of the games of the user.
        return response()->json([
            'user_nickname' => $user->nickname,
            'game_stats' => $userStats,
            'games' => $gamesData
        ], 200);
    }


    /**
     * @OA\Delete(
     *     path="/players/{id}/games",
     *     tags={"Games"},
     *     summary="Delete all game history for a player",
     *     description="This endpoint deletes all game history for a player by their UUID. Only authenticated users with the appropriate permissions can access this endpoint.",
     *     operationId="deleteAllGames",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the player to delete game history for",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="All games deleted successfully"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Forbidden"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player not found"),
     *         )
     *     ),
     * )
     */
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
