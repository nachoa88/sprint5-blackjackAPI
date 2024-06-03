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
    /**
     * @OA\Get(
     *     path="/players",
     *     tags={"Users"},
     *     summary="Show all players & their average win percentages",
     *     description="This endpoint returns all players and their average win percentages. Only authenticated users with the appropriate permissions can access this endpoint.",
     *     operationId="getAllPlayers",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="total_wins_average", type="number", example="0.52"),
     *             @OA\Property(property="total_losses_average", type="number", example="0.30"),
     *             @OA\Property(property="total_ties_average", type="number", example="0.18"),
     *             @OA\Property(property="user_details", type="array", @OA\Items(ref="#/components/schemas/User")),
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
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/players/{id}",
     *     tags={"Users"},
     *     summary="Update nickname for player",
     *     description="This endpoint updates the nickname for a player. Only the player themselves can access this endpoint.",
     *     operationId="updatePlayer",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the user to update",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         description="Nickname to update",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nickname", type="string", example="NewNickname"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nickname modified successfully"),
     *             @OA\Property(property="new nickname", type="string", example="NewNickname"),
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
     *             @OA\Property(property="message", type="string", example="User not found"),
     *         )
     *     ),
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/players/{id}",
     *     tags={"Users"},
     *     summary="Delete a user",
     *     description="This endpoint deletes a user by its UUID. Only authenticated users with the appropriate permissions can access this endpoint.",
     *     operationId="deleteUser",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="UUID of the user to delete",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User deleted successfully"),
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
     *             @OA\Property(property="message", type="string", example="User not found"),
     *         )
     *     ),
     * )
     */
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
