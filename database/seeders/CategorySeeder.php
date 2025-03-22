<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear categorías predefinidas
        $categories = [
            [
                'name' => 'Tecnología',
                'slug' => 'tecnologia',
                'description' => 'Artículos sobre tecnología y programación'
            ],
            [
                'name' => 'Ciencia',
                'slug' => 'ciencia',
                'description' => 'Artículos sobre ciencia y descubrimientos'
            ],
            [
                'name' => 'Deportes',
                'slug' => 'deportes',
                'description' => 'Artículos sobre deportes y actividad física'
            ],
            [
                'name' => 'Cultura',
                'slug' => 'cultura',
                'description' => 'Artículos sobre cultura y arte'
            ],
            [
                'name' => 'Vida',
                'slug' => 'vida',
                'description' => 'Artículos sobre estilo de vida y bienestar'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Crear categorías aleatorias adicionales
        Category::factory(5)->create();
    }
} 