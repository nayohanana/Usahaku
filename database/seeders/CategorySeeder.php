<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Kretek', 'slug' => 'kretek', 'description' => 'Rokok kretek dengan cengkeh'],
            ['name' => 'Putih', 'slug' => 'putih', 'description' => 'Rokok putih tanpa cengkeh'],
            ['name' => 'Menthol', 'slug' => 'menthol', 'description' => 'Rokok dengan rasa mentol'],
            ['name' => 'Lokal', 'slug' => 'lokal', 'description' => 'Rokok produksi lokal'],
            ['name' => 'Premium', 'slug' => 'premium', 'description' => 'Rokok premium kelas atas'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}