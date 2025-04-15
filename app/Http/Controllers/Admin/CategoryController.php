<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('parent')->get(); // include parent info
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya ambil kategori induk (parent_id null)
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:categories,id', // Validasi parent_id jika ada
        ]);

        // Membuat kategori baru
        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        $subcategories = $category->subcategories->pluck('id')->toArray(); // ambil ID subkategori

        return view('admin.categories.edit', compact('category', 'categories', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Update kategori
        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->subcategories()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'Kategori ini memiliki subkategori dan tidak dapat dihapus.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
