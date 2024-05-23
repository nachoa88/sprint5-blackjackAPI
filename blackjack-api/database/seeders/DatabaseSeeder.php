<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use Spatie\Permission\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Ensure the roles and permissions are created
        $this->call(RolesAndPermissionsSeeder::class);

        // Create 10 users with the 'player' role
        $users = User::factory(10)->create()->each(function ($user) {
            $user->assignRole('player');
        });

        foreach ($users as $user) {
            Game::factory(5)->create(['user_uuid' => $user->uuid]);
        }

        // Create a user with the 'super-admin' role
        $superAdmin = User::factory()->create([
            'nickname' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => '123456789',
        ]);
        $superAdmin->assignRole('super-admin');

        // Create a user with the 'moderator' role
        $moderator = User::factory()->create([
            'nickname' => 'Moderator',
            'email' => 'moderator@mail.com',
            'password' => '123456789',
        ]);
        $moderator->assignRole('moderator');
    }
}
