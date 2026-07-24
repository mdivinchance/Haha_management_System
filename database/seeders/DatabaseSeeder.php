<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\DailyProductReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@haha.test',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        $manager1 = User::create([
            'name' => 'Jean Ntagungira',
            'username' => 'jean',
            'email' => 'jean@haha.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        $manager2 = User::create([
            'name' => 'Marie Uwimana',
            'username' => 'marie',
            'email' => 'marie@haha.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        $manager3 = User::create([
            'name' => 'Patrick Mugisha',
            'username' => 'patrick',
            'email' => 'patrick@haha.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'email_verified_at' => now(),
        ]);

        $managers = [$manager1, $manager2, $manager3];

        $categories = collect();
        foreach ($managers as $i => $manager) {
            $cats = Category::factory(1)->create(['user_id' => $manager->id]);
            $categories = $categories->concat($cats);
        }

        $products = Product::factory(10)->recycle($categories)->create();

        foreach ($products as $i => $product) {
            $product->update(['user_id' => $managers[$i % 3]->id]);
        }

        $paymentMethods = ['cash', 'mobile_money'];
        $now = Carbon::now();

        foreach ($products as $product) {
            $owner = $managers[array_search($product->user_id, array_map(fn($m) => $m->id, $managers))];
            for ($daysAgo = 30; $daysAgo >= 0; $daysAgo--) {
                if (rand(1, 3) === 1) {
                    $qty = rand(1, 10);
                    $price = $product->selling_price;
                    DailyProductReport::create([
                        'product_id' => $product->id,
                        'user_id' => $owner->id,
                        'report_date' => $now->copy()->subDays($daysAgo)->toDateString(),
                        'quantity_sold' => $qty,
                        'selling_price' => $price,
                        'total_revenue' => $qty * $price,
                        'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                        'notes' => null,
                    ]);
                }
            }
        }
    }
}
