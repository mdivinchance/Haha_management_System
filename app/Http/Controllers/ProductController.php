<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use App\Rules\NoSqlInjection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    public function show(Product $product): View
    {
        $product->load('category', 'dailyReports');
        return view('products.show', compact('product'));
    }

    public function create(): View
    {
        $categories = Category::all();
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
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
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
        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted.');
    }

    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
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
