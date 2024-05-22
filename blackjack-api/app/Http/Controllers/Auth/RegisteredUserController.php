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

        return response()->json(['message' => 'User created'], 201);
    }
}
