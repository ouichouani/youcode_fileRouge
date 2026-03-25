<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

    $user = User::inRandomOrder()->first() ;
    $post = Post::inRandomOrder()->first() ;
    
        return [
            'content' => fake()->sentence(10) ,
            'user_id' => $user->id ,
            'post_id' => $post->id 
        ];
    }
}
