<?php

namespace Database\Factories;

use App\Models\Milestone;
use Illuminate\Database\Eloquent\Factories\Factory;

class MilestoneFactory extends Factory
{
    protected $model = Milestone::class;

    public function definition(): array
    {
        return [
            'year'       => (string) fake()->numberBetween(2020, 2026),
            'label'      => ['pl' => fake()->sentence(3), 'en' => fake()->sentence(3)],
            'sort_order' => fake()->numberBetween(0, 10),
        ];
    }
}
