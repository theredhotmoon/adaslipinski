<?php

namespace Database\Factories;

use App\Models\FaqItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class FaqItemFactory extends Factory
{
    protected $model = FaqItem::class;

    public function definition(): array
    {
        return [
            'question'   => ['pl' => fake()->sentence().'?', 'en' => fake()->sentence().'?'],
            'answer'     => ['pl' => fake()->paragraph(), 'en' => fake()->paragraph()],
            'sort_order' => fake()->numberBetween(0, 10),
            'active'     => true,
        ];
    }
}
