<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Rules\NoSqlInjection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $query = Category::withCount('products');

        if (Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }

        $categories = $query->latest()->get();
        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $name = $request->input('name');

        $query = Category::where('name', $name);
        if (Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }
        if ($query->exists()) {
            return back()->withErrors(['name' => 'The category name has already been taken.'])->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoSqlInjection],
            'description' => ['nullable', 'string', new NoSqlInjection],
        ]);

        $validated['user_id'] = Auth::id();
        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created.');
    }

    public function edit(Category $category): View
    {
        if (Auth::user()->isManager()) {
            abort_if($category->user_id !== Auth::id(), 403);
        }
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        if (Auth::user()->isManager()) {
            abort_if($category->user_id !== Auth::id(), 403);
        }

        $name = $request->input('name');

        $query = Category::where('name', $name)->where('id', '!=', $category->id);
        if (Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }
        if ($query->exists()) {
            return back()->withErrors(['name' => 'The category name has already been taken.'])->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', new NoSqlInjection],
            'description' => ['nullable', 'string', new NoSqlInjection],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if (Auth::user()->isManager()) {
            abort_if($category->user_id !== Auth::id(), 403);
        }
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete category with existing products.');
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
