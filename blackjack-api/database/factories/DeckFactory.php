<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Deck;
use App\Models\DeckCard;

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
        // First create an empty deck, and then add 52 cards to it.
        return $this->afterCreating(function (Deck $deck) {
            $suits = ['hearts', 'diamonds', 'clubs', 'spades'];
            $values = ['2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K', 'A'];

            foreach ($suits as $suit) {
                foreach ($values as $value) {
                    DeckCard::factory()->create(['deck_id' => $deck->id, 'suit' => $suit, 'value' => $value]);
                }
            }
        });
    }
}
