<?php

namespace App\Http\Controllers;

use App\Models\DailyProductReport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MoneyReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = DailyProductReport::with('product');

        if (!auth()->user()->isSuperAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('date_from')) {
            $query->where('report_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('report_date', '<=', $request->date_to);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $reports = $query->latest('report_date')->get();
        $products = Product::all();

        $totals = (object) [
            'total_revenue' => $reports->sum('total_revenue'),
            'total_quantity' => $reports->sum('quantity_sold'),
            'report_count' => $reports->count(),
        ];

        $dailySummary = $reports->groupBy(fn ($r) => $r->report_date->format('Y-m-d'))->map(function ($day) {
            return (object) [
                'date' => $day->first()->report_date,
                'total_revenue' => $day->sum('total_revenue'),
                'total_quantity' => $day->sum('quantity_sold'),
                'count' => $day->count(),
            ];
        });

        return view('money-report.index', compact('reports', 'products', 'totals', 'dailySummary'));
    }
}
