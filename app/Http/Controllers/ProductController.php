<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Rules\NoSqlInjection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    private function scopeQuery($query)
    {
        if (Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }
        return $query;
    }

    public function index(): View
    {
        $products = $this->scopeQuery(Product::with('category'))->latest()->get();
        return view('products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        if (Auth::user()->isManager()) {
            abort_if($product->user_id !== Auth::id(), 403);
        }
        $product->load('category', 'dailyReports');
        return view('products.show', compact('product'));
    }

    public function create(): View
    {
        $query = Category::query();
        if (Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }
        $categories = $query->latest()->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => ['required', 'string', 'max:255', new NoSqlInjection],
            'description' => ['nullable', 'string', new NoSqlInjection],
            'sku' => ['required', 'string', 'max:100', new NoSqlInjection, 'unique:products'],
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'camera_image' => 'nullable|string',
        ]);

        $imagePath = $this->handleImageUpload($request);
        if ($imagePath) {
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image'], $validated['camera_image']);

        $validated['user_id'] = auth()->id();
        $product = Product::create($validated);

        if ($product->stock_quantity > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'change_amount' => $product->stock_quantity,
                'reason' => 'Initial stock',
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product): View
    {
        if (Auth::user()->isManager()) {
            abort_if($product->user_id !== Auth::id(), 403);
        }
        $query = Category::query();
        if (Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }
        $categories = $query->latest()->get();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        if (Auth::user()->isManager()) {
            abort_if($product->user_id !== Auth::id(), 403);
        }
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => ['required', 'string', 'max:255', new NoSqlInjection],
            'description' => ['nullable', 'string', new NoSqlInjection],
            'sku' => ['required', 'string', 'max:100', new NoSqlInjection, 'unique:products,sku,' . $product->id],
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'camera_image' => 'nullable|string',
        ]);

        $imagePath = $this->handleImageUpload($request, $product);
        if ($imagePath) {
            $validated['image_path'] = $imagePath;
        }

        unset($validated['image'], $validated['camera_image']);

        $stockChange = $validated['stock_quantity'] - $product->stock_quantity;

        $product->update($validated);

        if ($stockChange !== 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'change_amount' => $stockChange,
                'reason' => 'Stock updated via edit',
            ]);
        }

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if (Auth::user()->isManager()) {
            abort_if($product->user_id !== Auth::id(), 403);
        }
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        if (Auth::user()->isManager()) {
            abort_if($product->user_id !== Auth::id(), 403);
        }
        $validated = $request->validate([
            'change' => 'required|integer',
            'reason' => ['nullable', 'string', 'max:255', new NoSqlInjection],
        ]);

        $product->stock_quantity += $validated['change'];
        $product->save();

        StockMovement::create([
            'product_id' => $product->id,
            'change_amount' => $validated['change'],
            'reason' => $validated['reason'] ?? 'Manual adjustment',
        ]);

        return redirect()->back()->with('success', 'Stock adjusted.');
    }

    private function handleImageUpload(Request $request, ?Product $product = null): ?string
    {
        if ($request->hasFile('image')) {
            if ($product && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $filename, 'public');
            return 'products/' . $filename;
        }

        if ($request->filled('camera_image')) {
            if ($product && $product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $base64 = $request->input('camera_image');
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $ext = $matches[1] === 'jpeg' ? 'jpg' : $matches[1];
                $imageData = base64_decode(substr($base64, strpos($base64, ',') + 1));
                $filename = time() . '_' . uniqid() . '.' . $ext;
                Storage::disk('public')->put('products/' . $filename, $imageData);
                return 'products/' . $filename;
            }
        }

        return null;
    }
}
