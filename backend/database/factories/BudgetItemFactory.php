<?php

namespace Database\Factories;

use App\Models\BudgetItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetItemFactory extends Factory
{
    protected $model = BudgetItem::class;

    public function definition(): array
    {
        $word = fake()->unique()->word();

        return [
            'slug'       => fake()->unique()->slug(2),
            'name'       => ['pl' => "Terapia {$word}", 'en' => "Therapy {$word}"],
            'icon'       => 'body',
            'frequency'  => ['pl' => 'tygodniowo', 'en' => 'weekly'],
            'cost_pln'   => fake()->numberBetween(100, 2000),
            'note'       => ['pl' => fake()->sentence(), 'en' => fake()->sentence()],
            'sort_order' => fake()->numberBetween(0, 10),
            'active'     => true,
        ];
    }
}
