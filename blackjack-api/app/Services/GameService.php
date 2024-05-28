<?php

namespace App\Services;

use App\Models\User;

class GameService
{
    public function getPlayers()
    {
        return User::role('player')->get();
    }

    public function getGameStats(User $user): array
    {
        // Get the game stats of a user.
        $totalGames = $user->games->count();

        $wins = $user->wins;
        $losses = $user->losses;
        $ties = $user->ties;

        return [
            'win_percentage' => $totalGames > 0 ? round(($wins / $totalGames) * 100, 2) : 0,
            'lose_percentage' => $totalGames > 0 ? round(($losses / $totalGames) * 100, 2) : 0,
            'tie_percentage' => $totalGames > 0 ? round(($ties / $totalGames) * 100, 2) : 0,
        ];
    }


    // Calculate the game stats for all the players.
    public function calculateRanking()
    {
        $users = $this->getPlayers();
        // Get the game stats for all the players.
        foreach ($users as $user) {
            // Call the Accessor function from user model to get the game stats.
            $userStats = $this->getGameStats($user);
            // Add the game stats to the user object.
            $user->gameStats = $userStats;
        }

        return $users;
    }
}
