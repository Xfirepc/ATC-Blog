<?php


namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $categories = Category::all();

        // Crear posts para cada usuario
        foreach ($users as $user) {
            // Cada usuario tendrá entre 2 y 5 posts
            $numPosts = rand(2, 5);
            
            Post::factory($numPosts)
                ->create([
                    'user_id' => $user->id,
                    'category_id' => $categories->random()->id,
                ]);
        }
    }
} 