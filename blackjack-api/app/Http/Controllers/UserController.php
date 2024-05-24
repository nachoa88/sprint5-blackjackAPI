<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Game;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateNicknameRequest;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    public function index(Game $games): JsonResponse
    {
        // Check if the authenticated user has the role & permission to view players.
        Gate::authorize('viewAny', User::class);

        // Tots els jugadors amb el seu percentatge mitjà d’èxits 
        $users = User::all();

        // Games controller will have a method to calculate the win average of a user.
        // For now, I'll show the result of the games for each user ('result' variable).
        foreach ($users as $user) {
            // Calculate the game stats.
            $gameStats = $this->calculateGameStats($user->games);
            $user->games = $games->where('user_uuid', $user->uuid)->pluck('result');
        }

        return response()->json($users);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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

        // Calculate the game stats.
        $gameStats = $this->calculateGameStats($games);

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
            'game_stats' => $gameStats,
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

    // HELPER FUNCTION for percentage calculation.
    private function calculateGameStats($games): array
    {
        $totalGames = $games->count();

        $wins = $games->where('result', 'win')->count();
        $losses = $games->where('result', 'lose')->count();
        $ties = $games->where('result', 'tie')->count();

        return [
            'win_percentage' => $totalGames > 0 ? round(($wins / $totalGames) * 100, 2) : 0,
            'lose_percentage' => $totalGames > 0 ? round(($losses / $totalGames) * 100, 2) : 0,
            'tie_percentage' => $totalGames > 0 ? round(($ties / $totalGames) * 100, 2) : 0,
        ];
    }
}
