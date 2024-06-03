<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Game",
 *     type="object",
 *     @OA\Property(property="id", type="integer", description="The unique identifier of the game"),
 *     @OA\Property(property="user_uuid", type="string", description="The unique identifier of the user who played the game"),
 *     @OA\Property(property="deck_id", type="integer", description="The unique identifier of the deck used in the game"),
 *     @OA\Property(property="player_hand", type="string", description="The hand of the player in the game"),
 *     @OA\Property(property="dealer_hand", type="string", description="The hand of the dealer in the game"),
 *     @OA\Property(property="player_score", type="integer", description="The score of the player in the game"),
 *     @OA\Property(property="dealer_score", type="integer", description="The score of the dealer in the game"),
 *     @OA\Property(property="result", type="string", description="The result of the game, can be 'win', 'lose', or 'tie'"),
 * )
 */

class Game extends Model
{
    use HasFactory;

    const WIN = 'win';
    const LOSE = 'lose';
    const TIE = 'tie';

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
            return self::LOSE;
        } elseif ($playerScore > $dealerScore) {
            return self::WIN;
        } else {
            return self::TIE;
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
