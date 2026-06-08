<?php

namespace Database\Factories;

use App\Models\Foundation;
use App\Models\FoundationAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class FoundationAccountFactory extends Factory
{
    protected $model = FoundationAccount::class;

    public function definition(): array
    {
        return [
            'foundation_id' => Foundation::factory(),
            'currency'      => fake()->randomElement(['PLN', 'EUR', 'USD']),
            'iban'          => 'PL'.fake()->numerify('## #### #### #### #### #### ####'),
            'sort_order'    => fake()->numberBetween(0, 5),
        ];
    }
}
