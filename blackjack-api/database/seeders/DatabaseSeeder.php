<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Game;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        foreach ($users as $user) {
            Game::factory(5)->create(['user_uuid' => $user->uuid]);
        }

    }
}
