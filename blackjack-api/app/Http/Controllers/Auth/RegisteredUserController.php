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
     *     tags={"Auth"},
     *     summary="Register player",
     *     description="This is the endpoint to create and register a new player.",
     *     operationId="registerPlayer",
     *     @OA\Response(
     *         response=201,
     *         description="Player created successfully"
     *     ),
     *     @OA\RequestBody(
     *         description="Player registration details",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nickname", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
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

        return response()->json(['message' => 'Player created successfully'], 201);
    }
}
