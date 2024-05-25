<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

// use App\Traits\UUID;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    // Ver lo de UUID: si puede hacer con el protected $primaryKey = 'uuid' o con el use UUID trait creado.
    use HasFactory, Notifiable, HasApiTokens, HasRoles, HasUuids;
    protected $primaryKey = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'email',
        'password',
        'wins',
        'losses',
        'ties',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
    
    
    // Static function to find a user by UUID, it's static so it can be called without an instance of the class.
    public static function findByUuid($uuid): User
    {
        return static::where('uuid', $uuid)->firstOrFail();
    }

    // HELPER FUNCTION for user's game stats calculation.
    public function calculateGameStats(): array
    {
        $totalGames = $this->games->count();

        $wins = $this->wins;
        $losses = $this->losses;
        $ties = $this->ties;

        return [
            'win_percentage' => $totalGames > 0 ? round(($wins / $totalGames) * 100, 2) : 0,
            'lose_percentage' => $totalGames > 0 ? round(($losses / $totalGames) * 100, 2) : 0,
            'tie_percentage' => $totalGames > 0 ? round(($ties / $totalGames) * 100, 2) : 0,
        ];
    }

    // HELPER FUNCTION to calculate the total game stats for all the players. 
    // It's static so it can be called without an instance of the class, because it's not related to a specific user.
    public static function calculateTotalGameStats($users): array
    {
        // Set totals to 0.
        $totalWins = 0;
        $totalLosses = 0;
        $totalTies = 0;

        // For each user get the wins, losses and ties, and total amount of games.
        foreach ($users as $user) {
            $userStats = $user->calculateGameStats();
            $user->gameStats = $userStats;
            // For each user add the wins, losses and ties to the total.
            $totalWins += $user->wins;
            $totalLosses += $user->losses;
            $totalTies += $user->ties;
        }
        // Calculate the total amount of games.
        $totalGames = $totalWins + $totalTies + $totalLosses;
        // Calculate the average of wins, losses and ties.
        $winsAverage = $totalGames > 0 ? round(($totalWins / $totalGames) * 100, 2) : 0;
        $lossesAverage = $totalGames > 0 ? round(($totalLosses / $totalGames) * 100, 2) : 0;
        $tiesAverage = $totalGames > 0 ? round(($totalTies / $totalGames) * 100, 2) : 0;

        return [
            'total_wins' => $totalWins,
            'total_losses' => $totalLosses,
            'total_ties' => $totalTies,
            'total_games' => $totalGames,
            'wins_average' => $winsAverage,
            'losses_average' => $lossesAverage,
            'ties_average' => $tiesAverage,
        ];
    }


    // HELPER FUNCTION to add a game result to the user.
    public function addGameResult(string $result): void
    {
        if ($result === 'win') {
            $this->wins++;
        } elseif ($result === 'lose') {
            $this->losses++;
        } else {
            $this->ties++;
        }
        $this->save();
    }
}
