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
                'description' => 'Artículos sobre tecnología y programación'
            ],
            [
                'name' => 'Ciencia',
                'description' => 'Artículos sobre ciencia y descubrimientos'
            ],
            [
                'name' => 'Deportes',
                'description' => 'Artículos sobre deportes y actividad física'
            ],
            [
                'name' => 'Cultura',
                'description' => 'Artículos sobre cultura y arte'
            ],
            [
                'name' => 'Vida',
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