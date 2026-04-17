<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $category = Category::inRandomOrder()->first();

        return [
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'difficulty' => fake()->randomElement(["xxs", "xs", "s", "m", "l", "xl", "xxl"]),
            'priority' => fake()->randomElement(["xxs", "xs", "s", "m", "l", "xl", "xxl"]),
            'deadline' => fake()->dateTime('2026-12-20'),
            'done' => false,
            'streaks' => fake()->numberBetween(0, 365),
            'frequency' =>  fake()->randomElements(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"], fake()->numberBetween(1, 7)),
            // 'frequency' => fake()->boolean(30) ? ['OneTime'] : fake()->randomElements(["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday", "OneTime"], fake()->numberBetween(1, 7)),
            'category_id' => $category?->id,
            'user_id' => $user->id
            // 'user_id' => 1,
        ];
    }
}
