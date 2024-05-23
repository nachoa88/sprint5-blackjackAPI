<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_uuid',
        'player_hand',
        'dealer_hand',
        'player_score',
        'dealer_score',
        'result',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deck()
    {
        return $this->hasOne(Deck::class);
    }


    public function dealCards($numberOfCards)
    {
        // Implementation...
    }

    public function calculateScore($hand)
    {
        // Implementation...
    }

    public function determineResult($playerScore, $dealerScore)
    {
        // Implementation...
    }

    public function resetDeck()
    {
        $this->deck->cards()->update(['game_id' => null]);
    }
}
