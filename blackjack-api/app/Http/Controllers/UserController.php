<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    public function store(StoreUserRequest $request)
    {
        //     $user = new User;
        //     $user->uuid = Str::uuid();
        //     $user->name = $request->name;
        //     $user->email = $request->email;
        //     $user->password = Hash::make($request->password);
        //     $user->save();

        //     return response()->json($user, 201);
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

    public function update(Request $request, User $user)
    {
        //
    }

    public function destroy(User $user)
    {
        //
    }


    public function login(LoginRequest $request)
    {
        // $credentials = $request->only('email', 'password');

        // if (Auth::attempt($credentials)) {
        //     $user = Auth::user();
        //     $token = $user->createToken('MyApp')->accessToken;

        //     return response()->json(['token' => $token], 200);
        // } else {
        //     return response()->json(['error' => 'Unauthorised'], 401);
        // }
    }

    public function register(RegisterRequest $request)
    {
        $request->validated();

        $user = User::create([
            'uuid' => Str::uuid(),
            'nickname' => $request->nickname ?? 'Anonymous',
            'email' => $request->email,
            // The same as: 'password' => bcrypt($request->password),
            'password' => Hash::make($request->password),
        ]);

        // Assign a default role to the user
        $role = Role::findByName('player');
        $user->assignRole($role);

        return response()->json(['message' => 'User created'], 201);
    }
}
