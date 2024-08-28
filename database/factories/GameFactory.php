<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        return [
            'provider_id' => Provider::factory(), 
            'provider_game_id' => $this->faker->unique()->uuid, 
            'name' => $this->faker->name, 
            'status' => $this->faker->randomElement(['enabled', 'disabled']), 
            'type_id' => $this->faker->numberBetween(1, 10), 
            'type_description' => $this->faker->sentence, 
            'technology' => $this->faker->word, 
            'platform' => $this->faker->word, 
            'demo' => $this->faker->boolean, 
            'aspect_ratio' => $this->faker->word, 
            'technology_id' => $this->faker->numberBetween(1, 10), 
            'game_id_numeric' => $this->faker->numberBetween(1, 10000), 
            'frb_available' => $this->faker->boolean, 
            'variable_frb_available' => $this->faker->boolean, 
            'lines' => $this->faker->numberBetween(1, 100), 
            'data_type' => $this->faker->word, 
            'jurisdictions' => $this->faker->words(3, true), 
            'features' => $this->faker->words(3, true),
        ];
    }
}
