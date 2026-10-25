<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->words(2, true);

        return [
            'group' => 'timeline',
            'value' => (string) fake()->year(),
            'title' => ['nl' => 'NL '.$title, 'en' => 'EN '.$title],
            'subtitle' => null,
            'body' => ['nl' => fake()->sentence(), 'en' => fake()->sentence()],
            'list' => null,
            'icon' => 'star',
            'active' => true,
            'sort' => fake()->numberBetween(1, 50),
        ];
    }
}
