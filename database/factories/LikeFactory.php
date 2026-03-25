<?php

namespace Database\Factories;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $post = Post::inRandomOrder()->first();


        $user = User::query()
            ->whereDoesntHave('likes', function ($q) use ($post) {
                $q->where("post_id", $post->id);
            })->inRandomOrder()->first();


        return [
            'user_id' => $user->id ,
            'post_id' => $post->id ,
        ];
    }
}
