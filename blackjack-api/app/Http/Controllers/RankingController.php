<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Services\GameService;
use Illuminate\Http\JsonResponse;

class RankingController extends Controller
{
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
