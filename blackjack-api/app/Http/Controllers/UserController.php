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

    public function index(): JsonResponse
    {
        // Check if the authenticated user has the role & permission to view players.
        Gate::authorize('viewAny', User::class);

        // Tots els jugadors amb el seu percentatge mitjà d’èxits 
        $users = User::all();

        // Set totals to 0.
        $totalWins = 0;
        $totalLosses = 0;
        $totalTies = 0;

        // For each user get the wins, losses and ties, and total amount of games.
        foreach ($users as $user) {
            $userStats = $user->calculateGameStats();
            $user->gameStats = $userStats;
            // For each user add the wins, losses and ties to the total.
            $totalWins += $user->wins;
            $totalLosses += $user->losses;
            $totalTies += $user->ties;
        }
        // Calculate the total amount of games.
        $totalGames = $totalWins + $totalTies + $totalLosses;
        // Calculate the average of wins, losses and ties.
        $winsAverage = $totalGames > 0 ? round(($totalWins / $totalGames) * 100, 2) : 0;
        $lossesAverage = $totalGames > 0 ? round(($totalLosses / $totalGames) * 100, 2) : 0;
        $tiesAverage = $totalGames > 0 ? round(($totalTies / $totalGames)* 100, 2) : 0;

        return response()->json([
            'total_wins_average' => $winsAverage,
            'total_losses_average' => $lossesAverage,
            'total_ties_average' => $tiesAverage,
            'user_details' => $users,
        ]);
    }

    public function show($id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::where('uuid', $id)->first();
        // If the user does not exist, return a 404 error. (This is also handled in the request validation).
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        Gate::authorize('view', $user);

        // Get the games of the user.
        $games = Game::where('user_uuid', $user->uuid)
            ->select('player_hand', 'dealer_hand', 'player_score', 'dealer_score', 'result')
            ->get();

        // Prepare the games data.
        $gamesData = $games->map(function ($game) {
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

    public function edit(User $user)
    {
        //
    }

    public function update(UpdateNicknameRequest $request, $id)
    {
        // Get the user by its UUID.
        $user = User::where('uuid', $id)->first();
        // If the user does not exist, return a 404 error. (This is also handled in the request validation).
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

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

    public function destroy(User $user)
    {
        //
    }
}
