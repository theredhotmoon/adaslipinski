<?php

namespace Database\Factories;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    public function definition(): array
    {
        return [
            'name'       => fake()->company(),
            'sort_order' => fake()->numberBetween(0, 5),
        ];
    }
}
