<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{

    public function index(): JsonResponse
    {
        $users = User::all();

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

    public function update(UpdateUserRequest $request, $id)
    {
        // First, find the user by its UUID.
        $user = User::where('uuid', $id)->first();

        // If the user does not exist, return a 404 error.
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        // Then, validate the request.
        $request->validated();
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
