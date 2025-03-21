<?php
namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        // Crear comentarios para cada post
        foreach ($posts as $post) {
            // Cada post tendrá entre 3 y 8 comentarios
            $numComments = rand(3, 8);
            
            // Crear comentarios de diferentes usuarios
            for ($i = 0; $i < $numComments; $i++) {
                Comment::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $users->random()->id,
                ]);
            }
        }
    }
} 