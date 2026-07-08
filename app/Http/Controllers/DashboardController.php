<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $lowStockCount = Product::all()->filter(fn($p) => $p->isLowStock())->count();
        $inventoryValue = Product::sum(DB::raw('stock_quantity * purchase_price'));
        $recentProducts = Product::with('category')->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts', 'totalCategories', 'lowStockCount', 'inventoryValue', 'recentProducts'
        ));
    }
}
