<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@ps.net',
            'password' => bcrypt('password'),
        ]);

        User::factory()->create([
            'name' => 'Editor User',
            'email' => 'editor@ps.net',
            'password' => bcrypt('password'),
        ]);

        User::factory()->inactive()->create([
            'name' => 'Inactive User',
            'email' => 'inactive@ps.net',
            'password' => bcrypt('password'),
        ]);

        $cat1 = Category::factory()->create([
            'name' => 'New Cars',
            'slug' => 'new-cars',
            'description' => 'Articles about new cars',
        ]);

        $cat2 = Category::factory()->create([
            'name' => 'Dealers',
            'slug' => 'dealers',
            'description' => 'Articles about dealers',
        ]);

        Article::factory()
            ->count(10)
            ->create()
            ->each(function (Article $article) use ($cat1, $cat2) {
                $article->categories()->attach(fake()->randomElement([$cat1, $cat2]));
            });

    }
}
