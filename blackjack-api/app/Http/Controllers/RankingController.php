<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;

class RankingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/players/ranking",
     *     tags={"Ranking"},
     *     summary="Get the ranking of all players",
     *     description="This endpoint returns the ranking of all players. It is a public endpoint and does not require authentication.",
     *     operationId="getRanking",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ranking found successfully"),
     *             @OA\Property(
     *                 property="ranking", 
     *                 type="array", 
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="nickname", type="string"),
     *                     @OA\Property(
     *                         property="gameStats", 
     *                         type="object",
     *                         @OA\Property(property="win_percentage", type="number"),
     *                         @OA\Property(property="tie_percentage", type="number"),
     *                         @OA\Property(property="lose_percentage", type="number")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Ranking not found"),
     *         )
     *     ),
     * )
     */
    // Get the ranking of all players
    public function ranking(GameService $gameService): JsonResponse
    {
        // Calculate the ranking of all the players using GameService.
        $playersRanking = $gameService->calculateRanking();
        // Sort the ranking by win percentage and select only the nickname and game stats.
        $playersRanking = $playersRanking->sortByDesc('gameStats.win_percentage')->select('nickname', 'gameStats')->values();

        return response()->json([
            'message' => 'Ranking found successfully',
            'ranking' => $playersRanking,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/players/ranking/winner",
     *     tags={"Ranking"},
     *     summary="Get the best player and its stats",
     *     description="This endpoint returns the best player and their stats. It is a public endpoint and does not require authentication.",
     *     operationId="getBestPlayer",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Best player found successfully"),
     *             @OA\Property(property="user_nickname", type="string"),
     *             @OA\Property(
     *                 property="user_stats", 
     *                 type="object",
     *                 @OA\Property(property="win_percentage", type="number"),
     *                 @OA\Property(property="tie_percentage", type="number"),
     *                 @OA\Property(property="lose_percentage", type="number")
     *             )
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
    // Get the best player and its stats
    public function bestPlayer(GameService $gameService): JsonResponse
    {
        $playersRanking = $gameService->calculateRanking();
        // Get only first user.
        $bestUser = $playersRanking->sortByDesc('gameStats.win_percentage')->first();

        return response()->json([
            'message' => 'Best player found successfully',
            'user_nickname' => $bestUser->nickname,
            'user_stats' => $bestUser->gameStats,
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/players/ranking/loser",
     *     tags={"Ranking"},
     *     summary="Get the worst player and its stats",
     *     description="This endpoint returns the worst player and their stats. It is a public endpoint and does not require authentication.",
     *     operationId="getWorstPlayer",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Worst player found successfully"),
     *             @OA\Property(property="user_nickname", type="string"),
     *             @OA\Property(
     *                 property="user_stats", 
     *                 type="object",
     *                 @OA\Property(property="win_percentage", type="number"),
     *                 @OA\Property(property="tie_percentage", type="number"),
     *                 @OA\Property(property="lose_percentage", type="number")
     *             )
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
    // Get the worst player and its stats
    public function worstPlayer(GameService $gameService): JsonResponse
    {
        $playersRanking = $gameService->calculateRanking();
        // Get only last user.
        $worstUser = $playersRanking->sortBy('gameStats.win_percentage')->first();

        return response()->json([
            'message' => 'Worst player found successfully',
            'user_nickname' => $worstUser->nickname,
            'user_stats' => $worstUser->gameStats,
        ], 200);
    }
}
