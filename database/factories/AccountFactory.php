<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
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
            'name' => fake()->words(2, true) . ' Account',
            'type' => fake()->randomElement(['checking', 'savings', 'credit', 'investment']),
            'institution' => fake()->company(),
            'account_number' => fake()->numerify('****####'),
            'balance' => fake()->randomFloat(2, 0, 10000),
            'is_active' => true,
        ];
    }
}
