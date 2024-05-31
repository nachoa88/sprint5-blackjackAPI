<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticatedUserController extends Controller
{
    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Login"},
     *     summary="Log in a registered player",
     *     description="This is the endpoint to log in a registered player.",
     *     operationId="loginPlayer",
     *     @OA\RequestBody(
     *         description="Player login data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="test@mail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="123456789"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Player logged in successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged in successfully"),
     *             @OA\Property(property="token", type="string", example="token"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid login details",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid login details"),
     *         )
     *     ),
     * )
     */
    public function login(LoginRequest $request)
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
