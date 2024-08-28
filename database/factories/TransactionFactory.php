<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Game;
use App\Models\Provider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), 
            'game_id' => Game::factory(), 
            'round_id' => $this->faker->numberBetween(1, 1000),
            'amount' => $this->faker->numberBetween(100, 10000),
            'reference' => $this->faker->unique()->uuid,
            'provider_id' => Provider::factory(), 
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'round_details' => $this->faker->text,
            'type' => $this->faker->randomElement(['bet', 'result', 'refund']),
            'status' => $this->faker->randomElement(['processing', 'completed']),
        ];
    }
}
