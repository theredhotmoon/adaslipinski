<?php

namespace Database\Factories;

use App\Models\ProgressPost;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgressPostFactory extends Factory
{
    protected $model = ProgressPost::class;

    public function definition(): array
    {
        return [
            'tag'          => ['pl' => 'Terapia', 'en' => 'Therapy'],
            'title'        => ['pl' => fake()->sentence(4), 'en' => fake()->sentence(4)],
            'body'         => ['pl' => fake()->paragraph(), 'en' => fake()->paragraph()],
            'image_alt'    => ['pl' => fake()->sentence(3), 'en' => fake()->sentence(3)],
            'amount_pln'   => fake()->optional()->numberBetween(200, 6000),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
