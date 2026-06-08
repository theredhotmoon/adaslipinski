<?php

namespace Database\Factories;

use App\Models\YearSummary;
use Illuminate\Database\Eloquent\Factories\Factory;

class YearSummaryFactory extends Factory
{
    protected $model = YearSummary::class;

    public function definition(): array
    {
        $received = fake()->numberBetween(10000, 80000);
        $spent    = fake()->numberBetween(5000, $received);

        return [
            'year'         => fake()->unique()->numberBetween(2018, 2026),
            'received_pln' => $received,
            'spent_pln'    => $spent,
            'balance_pln'  => $received - $spent,
            'tax_1_5_pln'  => fake()->numberBetween(0, 15000),
        ];
    }
}
