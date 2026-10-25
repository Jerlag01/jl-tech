<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role_id' => Role::factory()->editor(),
            'locale' => 'nl',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::factory()->super(),
        ]);
    }

    /**
     * @param  array<string, list<string>>  $permissions
     */
    public function withPermissions(array $permissions): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => Role::factory()->withPermissions($permissions),
        ]);
    }

    /**
     * A client of the support portal: no website role.
     */
    public function customer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => null,
            'support_role' => UserRole::Customer,
        ]);
    }

    /**
     * A helpdesk agent: works in /agent, has no website role.
     */
    public function agent(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => null,
            'support_role' => UserRole::Agent,
        ]);
    }

    /**
     * A helpdesk administrator in /agent (all departments), without a website role.
     */
    public function helpdeskAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => null,
            'support_role' => UserRole::Admin,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    public function withoutRole(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => null,
        ]);
    }
}
