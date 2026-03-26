<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $user = User::inRandomOrder()->first() ;
        return [
            "title" => fake()->name(),
            "color" => fake()->hexColor(),
            "description" => fake()->text(),
            "user_id" => $user->id
        ];
    }
}
