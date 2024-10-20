<?php

namespace Database\Factories;

use App\Models\Thought;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thought>
 */
class ThoughtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => Thought::generateSlug(),
            'topic' => fake()->words(3, true),
            'content' => fake()->sentence(),
            'tags' => fake()->words(3, true),
            'open' => true,
            'user_id' => User::factory()
        ];
    }
}
