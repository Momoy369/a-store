<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\VariantValue;
use App\Models\VariantOption;
use App\Models\ProductVariantCombination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

use App\Notifications\LowStockNotification;
use App\Models\User;


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

    public function getData(Request $request)
    {
        $query = Product::with(['category.parent', 'variants', 'variantCombinations.variantValues', 'discount']); // tambahkan relasi jika perlu

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('image', function ($product) {
                $url = asset('storage/' . $product->image); // sesuaikan path penyimpanan gambar
                return '<img src="' . $url . '" alt="' . $product->name . '" width="50">';
            })
            ->addColumn('name', function ($product) {
                $name = $product->name ?? '-';
                $shortName = strlen($name) > 30 ? substr($name, 0, 30) . '...' : $name;

                return '<span data-bs-toggle="tooltip" data-bs-placement="top" title="' . e($name) . '">' . e($shortName) . '</span>';
            })
            ->addColumn('category', function ($product) {
                $categoryName = $product->category->name ?? '-';
                $parentName = $product->category->parent->name ?? null;

                return '<span class="badge bg-secondary">'
                    . $categoryName
                    . ($parentName ? ' - ' . $parentName : '')
                    . '</span>';
            })
            ->addColumn('stock', function ($product) {
                $variantStock = $product->variantCombinations->sum('stock');
                $displayStock = $variantStock > $product->stock ? $variantStock : $product->stock;

                return '<span class="badge bg-success">' . $displayStock . '</span>';
            })
            ->addColumn('price', function ($product) {
                $productPrice = $product->price;

                // Diskon dari produk utama
                if ($product->discount) {
                    $productPrice -= $productPrice * ($product->discount->discount_percentage / 100);
                }

                $lowestVariantPrice = null;

                foreach ($product->variantCombinations as $combination) {
                    $variantPrice = $combination->price > 0 ? $combination->price : $product->price;

                    if ($combination->discount_type === 'percent') {
                        $variantPrice -= $variantPrice * ($combination->discount_value / 100);
                    } elseif ($combination->discount_type === 'fixed') {
                        $variantPrice -= $combination->discount_value;
                    }

                    if (is_null($lowestVariantPrice) || $variantPrice < $lowestVariantPrice) {
                        $lowestVariantPrice = $variantPrice;
                    }
                }

                $finalPrice = ($lowestVariantPrice !== null && $lowestVariantPrice < $productPrice)
                    ? $lowestVariantPrice
                    : $productPrice;

                $hasDiscount = $finalPrice < $product->price;
                $discountPercentage = $hasDiscount
                    ? round((($product->price - $finalPrice) / $product->price) * 100)
                    : 0;

                // Format harga untuk tampilan
                if ($hasDiscount) {
                    return '
            <div>
                <span class="text-decoration-line-through small text-muted">Rp' . number_format($product->price, 0, ',', '.') . '</span><br>
                <span class="text-primary fw-bold">Rp' . number_format($finalPrice, 0, ',', '.') . '</span>
                <small class="text-success">(-' . $discountPercentage . '%)</small>
            </div>';
                } else {
                    return '<span class="fw-bold">Rp' . number_format($product->price, 0, ',', '.') . '</span>';
                }
            })
            ->addColumn('variant', function ($product) {
                $html = '';
                $shownCombinations = $product->variantCombinations->take(2);

                foreach ($shownCombinations as $combination) {
                    $html .= '<div class="small mb-1">';
                    foreach ($combination->variantValues as $value) {
                        $html .= '<span class="d-inline-block">' . $value->name . ': ' . $value->value . '</span><br>';
                    }
                    $html .= '<span class="text-muted">Stok: ' . $combination->stock . '</span>';
                    $html .= '</div>';
                }

                if ($product->variantCombinations->count() > 2) {
                    $html .= '<span class="badge bg-info">+' . ($product->variantCombinations->count() - 2) . ' lainnya</span>';
                }

                return $html;
            })
            ->addColumn('status', function ($product) {
                $badgeClass = $product->is_active ? 'bg-success' : 'bg-secondary';
                $label = $product->is_active ? 'Aktif' : 'Nonaktif';

                return '<span class="badge ' . $badgeClass . '">' . $label . '</span>';
            })
            ->addColumn('action', function ($product) {
                return view('admin.products.partials.actions', compact('product'))->render();
            })
            ->rawColumns(['image', 'name', 'status', 'action', 'variant', 'price', 'category', 'stock', 'status']) // biar HTML bisa dirender
            ->make(true);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|max:2048',
            'description' => 'nullable|string',
            'variant_options.*' => 'required|string', // Warna, Ukuran
            'variant_values.*.*' => 'required|string', // Merah, Biru
            'combinations.*.stock' => 'required|integer',
            'combinations.*.price' => 'nullable|numeric',
            'combinations.*.discount_type' => 'nullable|string|in:percent,fixed',
            'combinations.*.discount_value' => 'nullable|numeric',
            'combinations.*.variant_values' => 'required|array',
        ]);

        $product = new Product();
        $product->fill($request->only(['name', 'category_id', 'price', 'stock', 'description']));
        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }
        $product->save();

        $variantOptionMap = [];
        $variantValueMap = [];

        foreach ($request->input('variant_options', []) as $i => $optionName) {
            $option = $product->variantOptions()->create(['name' => $optionName]);
            $variantOptionMap[$optionName] = $option;

            foreach (explode(',', $request->input("variant_values.$i")) as $val) {
                $val = trim($val);
                $variantValue = $option->variantValues()->firstOrCreate(['value' => $val]);
                $variantValueMap["$optionName:$val"] = $variantValue->id;
            }
        }

        foreach ($request->input('combinations', []) as $combo) {
            $combination = new ProductVariantCombination();
            $combination->product_id = $product->id;
            $combination->stock = $combo['stock'];
            $combination->price = $combo['price'] ?? $product->price;
            $combination->discount_type = $combo['discount_type'] ?? null;
            $combination->discount_value = $combo['discount_value'] ?? 0;
            $combination->save();

            $variantValueIds = [];
            foreach ($combo['variant_values'] as $val) {
                $variantValueIds[] = $variantValueMap[$val] ?? null;
            }

            $combination->variantValues()->sync($variantValueIds);
            $combination->variant_value_ids = json_encode($variantValueIds);
            $combination->save();
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        // Pastikan eager loading relasi agar efisien
        $product->load(['variantCombinations.variantValues', 'variants', 'discount', 'category']);

        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::with([
            'productVariantCombinations.variantValues.variantOption'
        ])->findOrFail($id);

        // Ambil semua variant options dengan variant values
        $variantOptions = VariantOption::with('variantValues')->get();

        // Ambil ID variantValues yang digunakan oleh produk
        $usedVariantValueIds = collect($product->productVariantCombinations)
            ->pluck('variantValues')
            ->flatten()
            ->pluck('id')
            ->unique()
            ->toArray();

        // Ambil semua kategori untuk dropdown
        $categories = Category::all();

        // Kirimkan data ke view
        return view('admin.products.edit', compact('product', 'variantOptions', 'usedVariantValueIds', 'categories'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',

            'variants' => 'nullable|array',
            'variants.*.options' => 'required|array|min:1',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|numeric|min:0',
            'variants.*.discount_type' => 'nullable|in:fixed,percentage',
            'variants.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        // Update produk utama
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'category_id' => $validated['category_id'],
            'status' => $validated['status'],
        ]);

        // Hapus kombinasi lama
        $product->variantCombinations()->delete();

        // Tambah ulang kombinasi varian
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                $variantValueIds = [];

                foreach ($variantData['options'] as $optionData) {
                    $valueId = $optionData['value_id'];

                    // Pastikan value_id valid (opsional: validasi lebih kuat bisa ditambah)
                    $variantValueIds[] = $valueId;
                }

                // Buat kombinasi baru
                $product->variantCombinations()->create([
                    'variant_value_ids' => json_encode($variantValueIds),
                    'price' => $variantData['price'],
                    'stock' => $variantData['stock'],
                    'discount_type' => $variantData['discount_type'] ?? null,
                    'discount_value' => $variantData['discount_value'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
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

    public function lowStock()
    {
        $lowStockCombinations = ProductVariantCombination::with(['product', 'variantValues.variantOption'])
            ->where('stock', '<', 5)
            ->get();

        $admins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->get();

        foreach ($lowStockCombinations as $combination) {
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($combination));
            }
        }

        return view('admin.low-stock', compact('lowStockCombinations'));
    }


}
