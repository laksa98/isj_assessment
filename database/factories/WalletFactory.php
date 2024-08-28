<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Wallet;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Wallet::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'currency' => $this->faker->currencyCode,
            'balance' => $this->faker->numberBetween(1000, 100000),
            'bonus' => $this->faker->numberBetween(0, 5000),
        ];
    }
}
