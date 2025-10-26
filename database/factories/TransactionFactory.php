<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'account_id' => Account::factory(),
            'amount' => fake()->randomFloat(2, -500, 500),
            'description' => fake()->sentence(),
            'merchant' => fake()->company(),
            'auto_categorized' => false,
            'transaction_date' => fake()->dateTimeBetween('-30 days', 'now'),
            'external_id' => fake()->uuid(),
            'type' => fake()->randomElement(['debit', 'credit']),
            'status' => fake()->randomElement(['pending', 'posted', 'cancelled']),
        ];
    }
}
