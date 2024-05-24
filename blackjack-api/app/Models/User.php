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

    // HELPER FUNCTION for game stats calculation.
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
