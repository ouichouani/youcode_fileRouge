<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Report;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $post = Post::inRandomOrder()->first() ;

        $user = User::whereDoesntHave('reports' , function ($q) use ($post){
            $q->where('post_id' , $post->id ) ;
        })->inRandomOrder()->first() ;


        return [
            'description'  => fake()->sentence(10),
            'type' => fake()->randomElement(['spam',
                'harassment',
                'hate_speech',
                'violence',
                'nudity',
                'misinformation',
                'copyright',
                'scam',
                'other']),
            'post_id' => $post->id ,
            'user_id' => $user->id ,
        ];
    }
}
