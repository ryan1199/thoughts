<?php

namespace Database\Factories;

use App\Models\Reply;
use App\Models\Thought;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reply>
 */
class ReplyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => Reply::generateSlug(),
            'content' => fake()->sentence(),
            'pinned' => fake()->randomElement([true, false]),
            'user_id' => User::factory(),
            'thought_id' => Thought::factory(),
            'replied' => false,
        ];
    }
}
