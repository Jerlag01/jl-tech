<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'url' => null,
            'logo' => null,
            'in_marquee' => true,
            'in_logo_grid' => true,
            'active' => true,
            'sort' => fake()->numberBetween(1, 50),
        ];
    }
}
