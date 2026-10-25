<?php

namespace Database\Factories;

use App\Models\JobOffer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOffer>
 */
class JobOfferFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();

        return [
            'title' => ['nl' => 'NL '.$title, 'en' => 'EN '.$title],
            'badge' => ['nl' => 'Techniek', 'en' => 'Engineering'],
            'tags' => ['nl' => "Vast contract\nVoltijds", 'en' => "Permanent\nFull-time"],
            'description' => ['nl' => fake()->sentence(), 'en' => fake()->sentence()],
            'active' => true,
            'sort' => fake()->numberBetween(1, 50),
        ];
    }
}
