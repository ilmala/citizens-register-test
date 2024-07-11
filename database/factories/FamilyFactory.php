<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Family;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Family>
 */
class FamilyFactory extends Factory
{
    protected $model = Family::class;

    public function definition(): array
    {
        return [
            'name' => 'Famiglia ' . $this->faker->lastName(),
        ];
    }
}
