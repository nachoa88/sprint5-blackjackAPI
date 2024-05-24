<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        // This is a simple implementation of the game logic for Factory purposes.
        $playerScore = $this->faker->numberBetween(11, 21);
        $dealerScore = $this->faker->numberBetween(11, 21);
        // Set the result based on the scores.
        $result = 'tie';
        if ($playerScore > $dealerScore) {
            $result = 'win';
        } elseif ($playerScore < $dealerScore) {
            $result = 'lose';
        }

        return [
            'user_uuid' => User::factory(),
            'player_hand' => null,
            'dealer_hand' => null,
            'player_score' => $playerScore,
            'dealer_score' => $dealerScore,
            'result' => $result,
        ];
    }
}
