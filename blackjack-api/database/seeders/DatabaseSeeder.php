<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
use App\Models\Deck;
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

        // Create 5 users with the 'player' role
        $users = User::factory(5)->create()->each(function ($user) {
            $user->assignRole('player');
        });

        // Get the deck from the service container.
        $deck = app(Deck::class);
        // Create 2 games for each user with that deck, and set the user's wins, losses, and ties
        foreach ($users as $user) {
            $games = Game::factory(5)->create(['user_uuid' => $user->uuid, 'deck_id' => $deck->id]);
            foreach ($games as $game) {
                if ($game->result === 'win') {
                    $user->wins++;
                } elseif ($game->result === 'lose') {
                    $user->losses++;
                } else {
                    $user->ties++;
                }
            }
            $user->save();
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
