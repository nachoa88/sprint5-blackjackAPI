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
        //'deck_id',
        'status',
        //'player_hand',
        //'dealer_hand',
        //'player_score',
        //'dealer_score',
        //'result',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
