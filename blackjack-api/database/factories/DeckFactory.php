<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Deck;
use App\Models\Card;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deck>
 */
class DeckFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }


    public function configure()
    {
        // After creating an empty deck, will then add 52 cards to it.
        return $this->afterCreating(function (Deck $deck) {
            $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
            // Create each card name and value for the deck.
            $cards = [
                '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10,
                'J' => 10, 'Q' => 10, 'K' => 10, 'A' => 11
            ];
            // Set the card order to 1 and then increment it for each card.
            $card_order = 1;
            // Create a card for each suit and card name.
            foreach ($suits as $suit) {
                foreach ($cards as $card_name => $value) {
                    Card::factory()->create([
                        'deck_id' => $deck->id,
                        'suit' => $suit,
                        'card_name' => $card_name,
                        'value' => $value,
                        'dealt' => '0',
                        'order' => $card_order++
                    ]);
                }
            }
        });
    }
}
