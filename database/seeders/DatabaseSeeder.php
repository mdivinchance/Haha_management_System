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
        ]);

        $manager = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@haha.test',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        $categories = Category::factory(3)->create();
        $products = Product::factory(10)->recycle($categories)->create();

        $paymentMethods = ['cash', 'mobile_money'];
        $now = Carbon::now();

        foreach ($products as $product) {
            for ($daysAgo = 30; $daysAgo >= 0; $daysAgo--) {
                if (rand(1, 3) === 1) {
                    $qty = rand(1, 10);
                    $price = $product->selling_price;
                    DailyProductReport::create([
                        'product_id' => $product->id,
                        'user_id' => $manager->id,
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
