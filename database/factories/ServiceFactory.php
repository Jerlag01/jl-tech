<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->words(3, true);

        return [
            'slug' => str($title)->slug()->toString(),
            'title' => ['nl' => 'NL '.$title, 'en' => 'EN '.$title],
            'subtitle' => ['nl' => fake()->words(3, true), 'en' => fake()->words(3, true)],
            'summary' => ['nl' => fake()->sentence(), 'en' => fake()->sentence()],
            'description' => ['nl' => fake()->paragraph(), 'en' => fake()->paragraph()],
            'features' => ['nl' => "Eén\nTwee", 'en' => "One\nTwo"],
            'stats' => [['value' => '100+', 'label' => ['nl' => 'Projecten', 'en' => 'Projects']]],
            'icon' => 'bolt',
            'show_on_home' => true,
            'active' => true,
            'sort' => fake()->numberBetween(1, 50),
        ];
    }

    public function hidden(): static
    {
        return $this->state(fn (): array => ['active' => false]);
    }
}
