<?php

namespace Database\Factories;

use App\Models\Foundation;
use App\Models\FoundationLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoundationLinkFactory extends Factory
{
    protected $model = FoundationLink::class;

    public function definition(): array
    {
        return [
            'foundation_id' => Foundation::factory(),
            'label'         => fake()->sentence(3),
            'url'           => fake()->url(),
            'sort_order'    => fake()->numberBetween(0, 5),
        ];
    }
}
