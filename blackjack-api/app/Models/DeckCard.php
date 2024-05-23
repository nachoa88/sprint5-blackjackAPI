<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeckCard extends Model
{
    use HasFactory;

    public function deck()
    {
        return $this->belongsTo(Deck::class);
    }
}
