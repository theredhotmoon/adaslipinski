<?php

namespace Database\Factories;

use App\Models\Beneficiary;
use Illuminate\Database\Eloquent\Factories\Factory;

class BeneficiaryFactory extends Factory
{
    protected $model = Beneficiary::class;

    public function definition(): array
    {
        $first = fake()->firstName();

        return [
            'name'              => $first,
            'full_name'         => $first.' '.fake()->lastName(),
            'age'               => fake()->numberBetween(1, 17),
            'diagnosis'         => ['pl' => fake()->sentence(), 'en' => fake()->sentence()],
            'diagnosis_plain'   => ['pl' => fake()->sentence(), 'en' => fake()->sentence()],
            'hero_kicker'       => ['pl' => 'Razem damy radę', 'en' => 'Together we can do it'],
            'hero_title'        => ['pl' => "{$first}, walczymy o każdy ruch.", 'en' => "{$first}, fighting for every movement."],
            'hero_subtitle'     => ['pl' => fake()->sentence(), 'en' => fake()->sentence()],
            'cta_label'         => ['pl' => 'Wpłać', 'en' => 'Donate'],
            'cta_bar_label'     => ['pl' => 'Wpłać teraz', 'en' => 'Donate now'],
            'recurring_default' => true,
            'nfz_monthly_pln'   => fake()->numberBetween(0, 2000),
        ];
    }
}
