<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\FriendRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Report;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(20)->create();
        Category::factory(20)->create();
        // Task::factory(100)->create();
        // Post::factory(100)->create();
        // Like::factory(200)->create();
        Comment::factory(200)->create();
        Report::factory(5)->create();
        FriendRequest::factory(50)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
