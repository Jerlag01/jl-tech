<?php

namespace Database\Factories;

use App\Models\Role;
use App\Support\Permissions;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()).' role',
            'description' => fake()->sentence(),
            'is_super' => false,
            'permissions' => [],
        ];
    }

    public function super(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_super' => true,
        ]);
    }

    /**
     * @param  array<string, list<string>>  $permissions
     */
    public function withPermissions(array $permissions): static
    {
        return $this->state(fn (array $attributes) => [
            'permissions' => Permissions::sanitize($permissions),
        ]);
    }

    /**
     * Content editor: manages content and messages, not users, roles or settings.
     */
    public function editor(): static
    {
        return $this->withPermissions([
            'page_content' => ['view', 'update'],
            'page_blocks' => ['view', 'create', 'update', 'delete'],
            'services' => ['view', 'create', 'update', 'delete'],
            'projects' => ['view', 'create', 'update', 'delete'],
            'posts' => ['view', 'create', 'update', 'delete'],
            'clients' => ['view', 'create', 'update', 'delete'],
            'employees' => ['view', 'create', 'update', 'delete'],
            'job_offers' => ['view', 'create', 'update', 'delete'],
            'contact_messages' => ['view', 'update', 'delete'],
        ]);
    }
}
