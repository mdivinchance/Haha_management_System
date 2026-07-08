<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@haha.test',
            'password' => Hash::make('password'),
        ]);

        $categories = Category::factory(3)->create();

        Product::factory(10)->recycle($categories)->create();
    }
}
