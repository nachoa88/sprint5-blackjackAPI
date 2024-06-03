<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
// use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{

    /**
     * @OA\Post(
     *     path="/players",
     *     tags={"Register"},
     *     summary="Register a new player",
     *     description="This is the endpoint to create and register a new player.",
     *     operationId="registerPlayer",
     *     @OA\RequestBody(
     *         description="Player registration details",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nickname", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Player created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Player created successfully"),
     *             @OA\Property(property="uuid", type="string", example="3fa85f64-5717-4562-b3fc-2c963f66afa6"),
     *         )
     *     )
     * )
     */
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

        // Assign a default role to the user, which is 'player' from the 'api' guard:
        // $role = Role::findByName('player', 'api'); - $user->assignRole($role);
        // This can be solved by changing the default guard in the config/auth.php file and then:
        $user->assignRole('player');

        return response()->json([
            'message' => 'Player created successfully',
            'uuid' => $user->uuid,
        ], 201);
    }
}
