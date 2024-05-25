<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
// use Illuminate\Http\Request;
use App\Http\Requests\UpdateNicknameRequest;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // Show all players & their average win percentages
    public function index(): JsonResponse
    {
        // Check if the authenticated user has the role & permission to view players.
        Gate::authorize('viewAny', User::class);

        // Tots els jugadors amb el seu percentatge mitjà d’èxits 
        $users = User::all();

        // User helper function to calculate the game stats for all the players:
        $stats = User::calculateTotalGameStats($users);

        return response()->json([
            'total_wins_average' => $stats['wins_average'],
            'total_losses_average' => $stats['losses_average'],
            'total_ties_average' => $stats['ties_average'],
            'user_details' => $users,
        ]);
    }

    // Show info of one player
    public function show($id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::findByUuid($id);

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
            'game_stats' => $user->calculateGameStats(),
            'games' => $gamesData
        ]);
    }

    // Update Nickname for player
    public function update(UpdateNicknameRequest $request, $id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::findByUuid($id);

        // Check if the authenticated user can update the user, and has roles & permissions.
        Gate::authorize('update', $user);

        // Update the user's nickname, or set it to 'Anonymous' if no nickname is provided.
        $user->nickname = $request['nickname'] ?? 'Anonymous';
        // Save the user.
        $user->save();

        return response()->json([
            'message' => 'Nickname modified successfully',
            'new nickname' => $user->nickname
        ]);
    }

    // Delete all game history for player
    public function destroyGames($id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::findByUuid($id);

        // Check if the authenticated user can delete the user's games, and has roles & permissions.
        Gate::authorize('deleteGames', $user);

        // Delete the user's games.
        $user->games()->delete();

        // Reset user stats for wins, losses and ties.
        $user->wins = 0;
        $user->losses = 0;
        $user->ties = 0;
        $user->save();

        return response()->json(['message' => 'All games deleted successfully']);
    }

    // Get the best player and its stats
    public function best(): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $users = User::all();
        // Get the game stats for all the players.
        foreach ($users as $user) {
            $userStats = $user->calculateGameStats();
            $user->gameStats = $userStats;
        }
        // Get the user with the best win percentage.
        $bestUser = $users->sortByDesc('gameStats.win_percentage')->first();

        return response()->json([
            'message' => 'Best player found successfully',
            'user_nickname' => $bestUser->nickname,
            'user_stats' => $bestUser->gameStats,
            'user_details' => $bestUser,
        ]);
    }

    // Get the worst player and its stats
    public function worst(): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $users = User::all();
        // Get the game stats for all the players.
        foreach ($users as $user) {
            $userStats = $user->calculateGameStats();
            $user->gameStats = $userStats;
        }
        // Get the user with the worst win percentage.
        $worstUser = $users->sortBy('gameStats.win_percentage')->first();

        return response()->json([
            'message' => 'Worst player found successfully',
            'user_nickname' => $worstUser->nickname,
            'user_stats' => $worstUser->gameStats,
            'user_details' => $worstUser,
        ]);
    }
}
