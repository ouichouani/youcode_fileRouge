<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\FriendRequest;
use App\Models\Like;
use App\Models\Log;
use App\Models\Post;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // User::factory()->create([
        //     'name' => 'abdelhakim',
        //     'email' => 'abdelhakim@gmail.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make("abdelhakim@gmail.com"),
        //     'remember_token' => Str::random(10),
        //     'role' => "Admin",
        //     'bio' => fake()->sentence(),
        //     'score' => fake()->numberBetween(0, 1000),
        // ]);
        User::factory()->create([
            'name' => 'abdelhakim2',
            'email' => 'abdelhakim2@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make("abdelhakim2@gmail.com"),
            'remember_token' => Str::random(10),
            'role' => "Moderator",
            'bio' => fake()->sentence(),
            'score' => fake()->numberBetween(0, 1000),
        ]);

        // User::factory(20)->create();
        // Category::factory(20)->create();
        // Task::factory(10)->create();
        // Task::factory(100)->create();
        // Post::factory(100)->create();
        // Like::factory(200)->create();
        // Comment::factory(200)->create();
        // Report::factory(5)->create();
        // FriendRequest::factory(100)->create();
        // Log::factory(90)->create();

        // Category::factory(10)->create();



    }
}
