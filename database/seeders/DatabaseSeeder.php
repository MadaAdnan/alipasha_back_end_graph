<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $city = City::create([
            'name' => 'إدلب',
            'is_active' => true,
            'is_delivery' => true,
            'is_main' => true,
        ]);
        $city->children()->create([
            'name' => 'سرمدا',
            'is_active' => true,
            'is_delivery' => true,
        ]);
        $city->children()->create([
            'name' => 'الدانا',
            'is_active' => true,
            'is_delivery' => true,
        ]);
        /*\App\Models\Category::factory(10)->create();*/
        $categoryProduct = Category::create([
            'name' => 'مركبات',
            'is_main' => true,
            'has_color' => true,
            'type' => CategoryTypeEnum::PRODUCT->value,
            'is_active'=>1,
        ]);

        $categoryProduct->children()->create([
            'name' => 'متورات',
            'is_main' => false,
            'has_color' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'is_active'=>1,
        ]);
        $categoryNews = Category::create([
            'name' => 'أخبار',
            'is_main' => true,
            'has_color' => false,
            'type' => CategoryTypeEnum::NEWS->value,
            'is_active'=>1,
        ]);
        $categoryNews->children()->create([
            'name' => 'تقني',
            'is_main' => false,
            'has_color' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'is_active'=>1,
        ]);
        $categoryNews->children()->create([

            'name' => 'رياضة',
            'is_main' => false,
            'has_color' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'is_active'=>1,

        ]);
        $categoryJob = Category::create([
            'name' => 'وظائف',
            'is_main' => true,
            'has_color' => false,
            'type' => CategoryTypeEnum::JOB->value,
            'is_active'=>1,
        ]);
        $categoryJob->children()->create([

            'name' => 'أعمال حرة',
            'is_main' => false,
            'has_color' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'is_active'=>1,
        ]);
        $categoryJob->children()->create([
            'name' => 'وظائف مكتبية',
            'is_main' => false,
            'has_color' => false,
            'created_at' => now(),
            'updated_at' => now(),
            'is_active'=>1,
        ]);
        $this->call(UserSeeder::class);
        \App\Models\User::factory(100)->create();
        \App\Models\Product::factory(100)->create();
        Color::create([
            'name' => 'أحمر',
            'code' => '#FF0000',
        ]);
        Color::create([
            'name' => 'أسود',
            'code' => '#000000',
        ]);
        Color::create([
            'name' => 'أبيض',
            'code' => '#FFFFFF',
        ]);
        Color::create([
            'name' => 'أخضر',
            'code' => '#00FF00',
        ]);
        Color::create([
            'name' => 'أزرق',
            'code' => '#0000FF',
        ]);
        Color::create([
            'name' => 'برتقالي',
            'code' => '#FFFF00',
        ]);


    }
}
