<?php

namespace Database\Factories;

use App\Models\Foundation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoundationFactory extends Factory
{
    protected $model = Foundation::class;

    public function definition(): array
    {
        return [
            'name'       => 'Fundacja '.fake()->lastName(),
            'krs'        => (string) fake()->numerify('00000#####'),
            'nip'        => fake()->numerify('###-##-##-###'),
            'regon'      => (string) fake()->numerify('#########'),
            'cel'        => fake()->name().' '.fake()->numerify('###/L'),
            'address'    => fake()->address(),
            'web'        => fake()->domainName(),
            'blik_phone' => fake()->numerify('### ### ###'),
            'email'      => fake()->safeEmail(),
            'phone'      => fake()->phoneNumber(),
        ];
    }
}
