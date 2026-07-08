<?php

namespace App\Http\Controllers;

use App\Models\DailyProductReport;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyReportController extends Controller
{
    public function create(Product $product): View
    {
        return view('daily-reports.create', compact('product'));
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'quantity_sold' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['total_revenue'] = $validated['quantity_sold'] * $validated['selling_price'];
        $validated['product_id'] = $product->id;

        DailyProductReport::create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Daily report saved.');
    }

    public function edit(Product $product, DailyProductReport $dailyReport): View
    {
        return view('daily-reports.edit', compact('product', 'dailyReport'));
    }

    public function update(Request $request, Product $product, DailyProductReport $dailyReport): RedirectResponse
    {
        $validated = $request->validate([
            'report_date' => 'required|date',
            'quantity_sold' => 'required|integer|min:1',
            'selling_price' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $validated['total_revenue'] = $validated['quantity_sold'] * $validated['selling_price'];

        $dailyReport->update($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Daily report updated.');
    }

    public function destroy(Product $product, DailyProductReport $dailyReport): RedirectResponse
    {
        $dailyReport->delete();

        return redirect()->route('products.show', $product)
            ->with('success', 'Daily report deleted.');
    }
}
