<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DailyProductReport;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->isSuperAdmin()) {
            return $this->superAdminDashboard($request);
        }

        return $this->managerDashboard();
    }

    private function superAdminDashboard(Request $request)
    {
        $period = $request->get('period', 'today');
        $managers = User::where('role', 'manager')->get();

        $totalManagers = $managers->count();
        $activeManagers = $managers->where('is_active', true)->count();

        $query = DailyProductReport::query();

        if ($period === 'today') {
            $query->whereDate('report_date', Carbon::today());
        } elseif ($period === 'week') {
            $query->whereBetween('report_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } elseif ($period === 'month') {
            $query->whereMonth('report_date', Carbon::now()->month)
                  ->whereYear('report_date', Carbon::now()->year);
        }

        $totalRevenue = (clone $query)->sum('total_revenue');
        $totalQuantity = (clone $query)->sum('quantity_sold');
        $totalTransactions = (clone $query)->count();

        $managerStats = $managers->map(function ($manager) use ($period) {
            $query = DailyProductReport::where('user_id', $manager->id);

            if ($period === 'today') {
                $query->whereDate('report_date', Carbon::today());
            } elseif ($period === 'week') {
                $query->whereBetween('report_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($period === 'month') {
                $query->whereMonth('report_date', Carbon::now()->month)
                      ->whereYear('report_date', Carbon::now()->year);
            }

            $totalRevenue = $query->sum('total_revenue');
            $totalQuantity = $query->sum('quantity_sold');
            $totalTransactions = $query->count();

            $cashRevenue = (clone $query)->where('payment_method', 'cash')->sum('total_revenue');
            $momoRevenue = (clone $query)->where('payment_method', 'mobile_money')->sum('total_revenue');

            return [
                'manager' => $manager,
                'total_revenue' => $totalRevenue,
                'total_quantity' => $totalQuantity,
                'total_transactions' => $totalTransactions,
                'cash_revenue' => $cashRevenue,
                'momo_revenue' => $momoRevenue,
            ];
        });

        $dailyBreakdown = DailyProductReport::selectRaw('report_date, user_id, SUM(total_revenue) as revenue, SUM(quantity_sold) as quantity, COUNT(*) as transactions')
            ->when($period === 'today', fn($q) => $q->whereDate('report_date', Carbon::today()))
            ->when($period === 'week', fn($q) => $q->whereBetween('report_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]))
            ->when($period === 'month', fn($q) => $q->whereMonth('report_date', Carbon::now()->month)->whereYear('report_date', Carbon::now()->year))
            ->groupBy('report_date', 'user_id')
            ->with('user:id,name')
            ->orderBy('report_date', 'desc')
            ->get();

        return view('dashboard-super-admin', compact(
            'period', 'totalManagers', 'activeManagers', 'totalRevenue',
            'totalQuantity', 'totalTransactions', 'managerStats', 'dailyBreakdown'
        ));
    }

    private function managerDashboard()
    {
        $userId = Auth::id();
        $totalProducts = Product::where('user_id', $userId)->count();
        $totalCategories = Category::whereHas('products', fn($q) => $q->where('user_id', $userId))->count();
        $lowStockCount = Product::where('user_id', $userId)->lowStock()->count();
        $inventoryValue = Product::where('user_id', $userId)->sum(DB::raw('stock_quantity * purchase_price'));
        $recentProducts = Product::with('category')->where('user_id', $userId)->latest()->take(5)->get();

        return view('dashboard', compact(
            'totalProducts', 'totalCategories', 'lowStockCount', 'inventoryValue', 'recentProducts'
        ));
    }
}
