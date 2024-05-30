<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
// use Illuminate\Http\Request;
use App\Http\Requests\UpdateNicknameRequest;
use App\Services\GameService;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // Show all players & their average win percentages
    public function getAll(GameService $gameService): JsonResponse
    {
        // Check if the authenticated user has the role & permission to view players.
        Gate::authorize('viewAny', User::class);

        // Calculate the ranking of all the players using GameService.
        $playersRanking = $gameService->calculateRanking();

        // Get the average win, tie and lose percentage for all the players.
        $totalWinsAverage = round($playersRanking->avg('gameStats.win_percentage'), 2);
        $totalTiesAverage = round($playersRanking->avg('gameStats.tie_percentage'), 2);
        $totalLossesAverage = round($playersRanking->avg('gameStats.lose_percentage'), 2);

        
        return response()->json([
            'total_wins_average' => $totalWinsAverage,
            'total_losses_average' => $totalLossesAverage,
            'total_ties_average' => $totalTiesAverage,
            'user_details' => $playersRanking,
        ], 200);
    }

    // Update Nickname for player
    public function update(UpdateNicknameRequest $request, $id): JsonResponse
    {
        // Get the user by its UUID.
        $user = User::findOrFail($id);

        // Check if the authenticated user can update the user, and has roles & permissions.
        Gate::authorize('update', $user);

        // Update the user's nickname, or set it to 'Anonymous' if no nickname is provided.
        $user->nickname = $request['nickname'] ?? 'Anonymous';
        // Save the user.
        $user->save();

        return response()->json([
            'message' => 'Nickname modified successfully',
            'new nickname' => $user->nickname
        ], 200);
    }

    // Delete a user by its UUID
    public function destroy($id): JsonResponse
    {
        Gate::authorize('deleteUser', User::class);
        
        $userToDelete = User::findOrFail($id);
        
        $userToDelete->delete();

        return response()->json([
            'message' => 'User deleted successfully',
        ], 200);
    }
}
