<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticatedUserController extends Controller
{

    public function store(LoginRequest $request)
    {
        // Validate request data.
        $request->validated();
        // Check if user with the email exists.
        $user = User::whereEmail($request['email'])->first();
        // Check if the password is correct (password must be compared decrypted).
        if (!$user || !Hash::check($request['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid login details',
            ], 401);
        } else {
            $token = $user->createToken('loginToken')->accessToken;
            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token
            ], 200);
        }
    }
}
