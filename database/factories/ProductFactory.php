<?php

namespace Database\Factories;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use mysql_xdevapi\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paragraph = fake()->paragraph;
        $category=Category::where('is_main',true)->inRandomOrder()->first();

      // dd($categories);
        return [
            'name' => fake()->sentence,
            'info' => $paragraph,
            'expert' => \Str::words($paragraph, 10),
            'category_id' => $category->id,
            'type' =>$category->type,
            'sub1_id' => $category->children?->first()?->id,
            'user_id' =>User::inRandomOrder()->first()?->id,
            'city_id' => fake()->randomElement([1,2,3]),
            'active' => fake()->randomElement(['active','pending','block'])
        ];
    }
}
