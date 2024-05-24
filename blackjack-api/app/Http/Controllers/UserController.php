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

    public function show(User $user): JsonResponse
    {
        $user = User::find($user->id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
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
