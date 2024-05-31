<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="uuid", type="string", description="The unique identifier of the user"),
 *     @OA\Property(property="nickname", type="string", description="The nickname of the user"),
 *     @OA\Property(property="email", type="string", description="The email of the user"),
 *     @OA\Property(property="wins", type="integer", description="The number of wins of the user"),
 *     @OA\Property(property="losses", type="integer", description="The number of losses of the user"),
 *     @OA\Property(property="ties", type="integer", description="The number of ties of the user"),
 * )
 */

class User extends Authenticatable
{
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

    // Mutator for game result. Mutators are called automatically when setting the value of an attribute on the model.
    // In this case, it's called when setting the game_result = 'result' attribute.
    public function setGameResultAttribute(string $result): void
    {
        if ($result === 'win') {
            $this->wins++;
        } elseif ($result === 'lose') {
            $this->losses++;
        } else {
            $this->ties++;
        }
    }
}
