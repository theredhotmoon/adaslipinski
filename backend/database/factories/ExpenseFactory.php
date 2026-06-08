<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'expense_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'description'  => ['pl' => fake()->sentence(3), 'en' => fake()->sentence(3)],
            'amount_pln'   => fake()->numberBetween(100, 6000),
            'vendor'       => fake()->company(),
            'has_invoice'  => true,
        ];
    }
}
