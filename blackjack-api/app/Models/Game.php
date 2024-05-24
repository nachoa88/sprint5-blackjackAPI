<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_uuid',
        'deck_id',
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
        return $this->belongsTo(Deck::class);
    }


    public function dealCards(int $numberOfCards): Collection
    {
        // Get the top cards that were not dealt yet from the deck for this game.
        $cards = $this->deck->cards()->where('dealt', false)->orderBy('order')->take($numberOfCards)->get();

        // Mark the dealt cards as dealt.
        foreach ($cards as $card) {
            $card->dealt = true;
            $card->save();
        }

        return $cards;
    }

    public function calculateScore(array $cards): int
    {
        // Calculate the score based on the cards and aces.
        $score = 0;
        $aces = 0;
        foreach ($cards as $card) {
            // Add the value of the card to the score.
            $score += $card['value'];

            // Count the number of aces (if there are).
            if ($this->isAce($card)) {
                $aces++;
            }
        }

        // Adjust score for aces (if necessary).
        $score = $this->adjustScoreForAces($score, $aces);

        return $score;
    }

    public function isAce($card): bool
    {
        return $card['card_name'] == 'A';
    }

    public function adjustScoreForAces(int $score, int $aces): int
    {
        // If the score is over 21 and there's an ace in the hand, subtract 10 for each ace.
        while ($score > 21 && $aces > 0) {
            $score -= 10;
            $aces--;
        }

        return $score;
    }

    public function determineResult(int $playerScore, int $dealerScore): string
    {
        // Compare the scores and determine the result of the game (win, loss, or tie).
        if ($playerScore > 21 || $dealerScore > $playerScore) {
            return 'lose';
        } elseif ($playerScore > $dealerScore) {
            return 'win';
        } else {
            return 'tie';
        }
    }

    public function shuffleDeck(): void
    {
        // Get all cards.
        $cards = $this->deck->cards()->get();

        // Shuffle the cards.
        $cards = $cards->shuffle();

        // Update each card's order and dealt status.
        foreach ($cards as $index => $card) {
            $card->order = $index;
            $card->dealt = false;
            $card->save();
        }
    }

    // HELPER FUNCTION: get the details of the cards in a hand.
    public function getHandDetails(Collection $hand): array
    {
        return $hand->map(function ($card) {
            return ['suit' => $card->suit, 'card_name' => $card->card_name, 'value' => $card->value];
        })->toArray();
    }
}
