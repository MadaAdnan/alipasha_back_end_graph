<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

         /*\App\Models\Category::factory(10)->create();*/
        Category::create([
            'name'=>'مركبات',
            'is_main'=>true,
            'has_color'=>true,
            'type'=>CategoryTypeEnum::PRODUCT->value,
        ])->parents()->insert([
          [
              'name'=>'سيارات',
              'is_main'=>false,
              'has_color'=>false,
              'created_at'=>now(),
              'updated_at'=>now(),
          ],
            [
                'name'=>'متورات',
                'is_main'=>false,
                'has_color'=>false,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
        ]);

        Category::create([
            'name'=>'أخبار',
            'is_main'=>true,
            'has_color'=>false,
            'type'=>CategoryTypeEnum::NEWS->value,
        ])->parents()->insert([
            [
                'name'=>'تقني',
                'is_main'=>false,
                'has_color'=>false,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name'=>'رياضة',
                'is_main'=>false,
                'has_color'=>false,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
        ]);
        Category::create([
            'name'=>'وظائف',
            'is_main'=>true,
            'has_color'=>false,
            'type'=>CategoryTypeEnum::JOB->value,
        ])->parents()->insert([
            [
                'name'=>'أعمال حرة',
                'is_main'=>false,
                'has_color'=>false,
                'created_at'=>now(),
                'updated_at'=>now(),
            ],
            [
                'name'=>'وظائف مكتبية',
                'is_main'=>false,
                'has_color'=>false,
                'created_at'=>now(),
                'updated_at'=>now(),
            ]
        ]);
        $this->call(UserSeeder::class);
         \App\Models\User::factory(100)->create();
         \App\Models\Product::factory(100)->create();

    }
}
