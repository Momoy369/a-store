<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['category.parent', 'variants', 'discount'])->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',

            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string',
            'variants.*.value' => 'required|string',
            'variants.*.stock' => 'required|integer',
            'variants.*.price' => 'nullable|numeric',
            'variants.*.discount_type' => 'nullable|string|in:percent,fixed',
            'variants.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        // Simpan image jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Ambil data product tanpa 'variants'
        $productData = collect($validated)->except('variants')->toArray();
        $product = Product::create($productData);

        // Simpan semua varian
        if (isset($validated['variants'])) {
            foreach ($validated['variants'] as $variant) {
                $product->variants()->create([
                    'name' => $variant['name'],
                    'value' => $variant['value'],
                    'stock' => $variant['stock'],
                    'price' => $variant['price'] ?? null,
                    'discount_type' => $variant['discount_type'] ?? null,
                    'discount_value' => $variant['discount_value'] ?? null,
                ]);
            }
        }

        // if ($variantPrice < 0) {
        //     return back()->withErrors(['Varian tidak boleh memiliki harga negatif.']);
        // }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan');
    }


    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Pastikan eager loading relasi agar efisien
        $product->load(['variants', 'discount', 'category']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Ambil semua kategori dan subkategori terkait produk
        $categories = Category::with('subcategories')->get(); // Memuat kategori dan subkategori terkait
        $subcategories = $product->category ? $product->category->subcategories : [];

        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',

            'variants' => 'nullable|array',
            'variants.*.name' => 'required|string',
            'variants.*.value' => 'required|string',
            'variants.*.stock' => 'required|integer',
            'variants.*.price' => 'nullable|numeric',
            'variants.*.discount_type' => 'nullable|string|in:percent,fixed',
            'variants.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        // Update gambar jika ada
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update produk utama
        $productData = collect($validated)->except('variants')->toArray();
        $product->update($productData);

        // Hapus dan ganti varian
        $product->variants()->delete();
        if (isset($validated['variants'])) {
            foreach ($validated['variants'] as $variant) {
                $product->variants()->create([
                    'name' => $variant['name'],
                    'value' => $variant['value'],
                    'stock' => $variant['stock'],
                    'price' => $variant['price'] ?? null,
                    'discount_type' => $variant['discount_type'] ?? null,
                    'discount_value' => $variant['discount_value'] ?? null,
                ]);
            }
        }
        // if ($variantPrice < 0) {
        //     return back()->withErrors(['Varian tidak boleh memiliki harga negatif.']);
        // }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus');
    }

    public function getSubcategories($categoryId)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['subcategories' => []]);
        }

        $subcategories = $category->subcategories; // Asumsi subkategori terkait disimpan dalam relasi subcategories

        return response()->json([
            'subcategories' => $subcategories,
        ]);
    }

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        return redirect()->back()->with('success', 'Status produk diperbarui.');
    }
}
