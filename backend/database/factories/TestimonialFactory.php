<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

class TestimonialFactory extends Factory
{
    protected $model = Testimonial::class;

    public function definition(): array
    {
        return [
            'quote_text'  => ['pl' => fake()->sentence(), 'en' => fake()->sentence()],
            'author_name' => fake()->name(),
            'author_role' => ['pl' => 'fizjoterapeutka', 'en' => 'physiotherapist'],
            'active'      => true,
        ];
    }
}
