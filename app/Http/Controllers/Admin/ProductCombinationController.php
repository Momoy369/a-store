<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductCombinationController extends Controller
{

    public function index()
    {
        $products = Product::with('variantCombinations.variantValues.option')->get();
        return view('admin.products.combinations.index', compact('products'));
    }

    public function create(Product $product)
    {
        $variantOptions = $product->variantOptions()->with('values')->get();

        return view('admin.products.combinations.create', compact('product', 'variantOptions'));
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'variant_value_ids' => 'required|array',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'discount_type' => 'nullable|in:none,percent,fixed',
            'discount_value' => 'nullable|numeric',
        ]);

        // Sort biar konsisten
        $newCombination = collect($validated['variant_value_ids'])->sort()->values()->all();

        // Ambil semua kombinasi yang sudah ada
        $existingCombinations = $product->variantCombinations->map(function ($comb) {
            return collect(json_decode($comb->variant_value_ids))->sort()->values()->all();
        });

        // Cek apakah sudah ada kombinasi yang sama
        foreach ($existingCombinations as $existing) {
            if ($existing == $newCombination) {
                return back()->withErrors(['variant_value_ids' => 'Kombinasi ini sudah ada.'])->withInput();
            }
        }

        // Simpan kombinasi
        $combination = $product->variantCombinations()->create([
            'variant_value_ids' => json_encode($validated['variant_value_ids']),
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'discount_type' => $validated['discount_type'] ?? 'none',
            'discount_value' => $validated['discount_value'] ?? 0,
        ]);

        $combination->variantValues()->sync($validated['variant_value_ids']);

        return redirect()->route('admin.products.combinations.edit', $product)->with('success', 'Kombinasi baru berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $variantOptions = $product->variantOptions()->with('values')->get();
        $combinations = $product->variantCombinations()->with('variantValues.option')->get();

        return view('admin.products.combinations.edit', compact('product', 'variantOptions', 'combinations'));
    }

    public function update(Request $request, Product $product, Product $combination)
    {
        $validated = $request->validate([
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'discount_type' => 'nullable|in:none,percent,fixed',
            'discount_value' => 'nullable|numeric',
        ]);

        $combination->update([
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'discount_type' => $validated['discount_type'] ?? 'none',
            'discount_value' => $validated['discount_value'] ?? 0,
        ]);

        return back()->with('success', 'Kombinasi berhasil diperbarui.');
    }

    public function destroy(Product $product, Product $combination)
    {
        $combination->delete();
        return back()->with('success', 'Kombinasi berhasil dihapus.');
    }

}
