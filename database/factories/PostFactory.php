<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::inRandomOrder()->first();
        return [
            'content' => fake()->sentence(5),
            'type' => fake()->randomElement(["Question", "History", "Encouragement"]),
            'visibility' => true,
            'user_id' => $user->id 
        ];
    }
}
