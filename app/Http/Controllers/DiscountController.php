<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function create()
    {
        $products = Product::all();
        return view('admin.discounts.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Discount::create([
            'product_id' => $validated['product_id'],
            'discount_percentage' => $validated['discount_percentage'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dibuat');
    }

    public function edit($id)
    {
        // Ambil data diskon dan data produk untuk dropdown
        $discount = Discount::findOrFail($id);
        $products = Product::all();

        // Tampilkan form edit dengan data diskon dan produk
        return view('admin.discounts.edit', compact('discount', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        // Temukan diskon yang ingin diperbarui
        $discount = Discount::findOrFail($id);

        // Perbarui data diskon
        $discount->update([
            'product_id' => $validated['product_id'],
            'discount_percentage' => $validated['discount_percentage'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil diperbarui');
    }

    public function index()
    {
        $discounts = Discount::all();
        return view('admin.discounts.index', compact('discounts'));
    }

    public function destroy($id)
    {
        // Temukan diskon yang ingin dihapus
        $discount = Discount::findOrFail($id);

        // Hapus diskon
        $discount->delete();

        return redirect()->route('admin.discounts.index')->with('success', 'Diskon berhasil dihapus');
    }
}
