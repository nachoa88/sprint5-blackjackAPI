<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Game;
use App\Models\Deck;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create a new game instance without persisting it to the database.
        $game = new Game();

        // Get the deck from the service container.
        $deck = app(Deck::class);

        // Associate the deck with the game.
        $game->deck()->associate($deck);

        // Shuffle the deck.
        $game->shuffleDeck();

        // Deal two cards to the player and the dealer & Use the helper function to get only details neede of the cards in a hand.
        $playerHand = $game->getHandDetails($game->dealCards(2));
        $dealerHand = $game->getHandDetails($game->dealCards(2));

        // Calculate the scores.
        $playerScore = $game->calculateScore($playerHand);
        $dealerScore = $game->calculateScore($dealerHand);

        // Determine the result of the game.
        $result = $game->determineResult($playerScore, $dealerScore);

        return [
            'user_uuid' => User::factory(),
            // These two fields need to be JSON encoded because they are arrays.
            'player_hand' => json_encode($playerHand),
            'dealer_hand' => json_encode($dealerHand),
            'player_score' => $playerScore,
            'dealer_score' => $dealerScore,
            'result' => $result,
            'deck_id' => $deck->id,
        ];
    }
}
