<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(3, true);

        return [
            'title' => ['nl' => 'NL '.$title, 'en' => 'EN '.$title],
            'category' => ['nl' => 'Categorie', 'en' => 'Category'],
            'badge' => ['nl' => '', 'en' => ''],
            'description' => ['nl' => fake()->sentence(), 'en' => fake()->sentence()],
            'stats' => [],
            'featured' => false,
            'highlight' => false,
            'show_on_home' => true,
            'active' => true,
            'sort' => fake()->numberBetween(1, 50),
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['featured' => true]);
    }
}
