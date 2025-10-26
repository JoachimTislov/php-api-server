<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Groceries', 'Gas', 'Restaurants', 'Entertainment', 'Shopping', 'Bills', 'Income']),
            'description' => fake()->sentence(),
            'color' => fake()->hexColor(),
            'icon' => fake()->randomElement(['🛒', '⛽', '🍕', '🎬', '🛍️', '💰', '💸']),
            'user_id' => User::factory(),
            'is_system' => false,
        ];
    }
}
