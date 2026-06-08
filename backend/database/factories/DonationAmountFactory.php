<?php

namespace Database\Factories;

use App\Models\DonationAmount;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonationAmountFactory extends Factory
{
    protected $model = DonationAmount::class;

    public function definition(): array
    {
        return [
            'amount_pln' => fake()->randomElement([20, 50, 100, 200]),
            'sort_order' => fake()->numberBetween(0, 5),
        ];
    }
}
