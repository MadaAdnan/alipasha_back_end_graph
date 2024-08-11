<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
           'level' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'seller_name' => 'ali-pasha',
            'phone' => '352681125699',
            'city_id' => City::first()?->id
        ]);
    }
}
