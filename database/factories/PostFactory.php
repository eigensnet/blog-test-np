<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'is_published' => 1,
        ];
    }
}
