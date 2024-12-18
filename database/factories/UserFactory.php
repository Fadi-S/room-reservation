<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "name" => fake()->name(),
            "username" => fake()->userName(),
            "is_admin" => false,
            "email" => fake()
                ->unique()
                ->safeEmail(),
            "remember_token" => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function admin(bool $isAdmin = true): static
    {
        return $this->state(
            fn(array $attributes) => [
                "is_admin" => $isAdmin,
            ],
        );
    }
}
