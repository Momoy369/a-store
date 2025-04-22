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
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'nullable|numeric',
            'stock' => 'nullable|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description' => 'nullable|string',
            'variant_options.*' => 'required|string',  // Nama opsi (Warna, Ukuran)
            'variant_values.*.*' => 'required|string', // Nilai untuk masing-masing opsi
            'combinations.*.stock' => 'required|integer',
            'combinations.*.price' => 'nullable|numeric',
            'combinations.*.discount_type' => 'nullable|string|in:percent,fixed',
            'combinations.*.discount_value' => 'nullable|numeric',
        ]);

        // Menyimpan Produk
        $product = new Product();
        $product->name = $request->input('name');
        $product->category_id = $request->input('category_id');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->description = $request->input('description');
        if ($request->hasFile('image')) {
            $product->image = $request->file('image')->store('products', 'public');
        }
        $product->save();

        $variantOptionNames = $request->input('variant_options', []);
        $variantOptionValues = $request->input('variant_values', []);

        // Menyimpan Variant Option dan Variant Value
        $variantOptionMap = []; // nama opsi => ID
        $variantValueMap = [];  // nama value => ID

        foreach ($variantOptionNames as $index => $optionName) {
            // Ini sudah benar, cukup 1 array karena relasi sudah mengatur product_id
            $variantOption = $product->variantOptions()->create([
                'name' => $optionName,
            ]);

            $variantOptionMap[$optionName] = $variantOption->id;

            $values = explode(',', $variantOptionValues[$index]); // pastikan ini array string
            foreach ($values as $value) {
                $value = trim($value);

                // Simpan variant value
                $variantValue = $variantOption->variantValues()->firstOrCreate([
                    'value' => $value
                ]);

                $variantValueMap[$value] = $variantValue->id;
            }
        }

        // Menyimpan Kombinasi Varian
        $combinations = $request->input('combinations', []);
        foreach ($combinations as $combinationData) {
            $combination = new ProductVariantCombination();
            $combination->product_id = $product->id;
            $combination->stock = $combinationData['stock'];
            $combination->price = $combinationData['price'] ?? $product->price;
            $combination->discount_type = $combinationData['discount_type'] ?? null;
            $combination->discount_value = $combinationData['discount_value'] ?? 0;
            $combination->save();

            $variantValueIds = []; // array untuk menyimpan ID dari variant values

            foreach ($combinationData['variant_values'] as $value) {
                $value = trim($value);
                if (isset($variantValueMap[$value])) {
                    // Menambahkan relasi dengan variant_value
                    $combination->variantValues()->attach($variantValueMap[$value]);
                    $variantValueIds[] = $variantValueMap[$value]; // Menyimpan ID variant values
                }
            }

            // Menyimpan variant_value_ids ke dalam field JSON
            $combination->variant_value_ids = json_encode($variantValueIds);
            $combination->save(); // Simpan ulang setelah menambahkan variant_value_ids
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
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'variants' => 'nullable|array',
            'variants.*.options' => 'required|array',
            'variants.*.options.*' => 'required|string',
            'variants.*.stock' => 'required|integer',
            'variants.*.price' => 'nullable|numeric',
            'variants.*.discount_type' => 'nullable|in:percent,fixed',
            'variants.*.discount_value' => 'nullable|numeric|min:0',
        ]);

        // Update image
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update basic product info
        $product->update(collect($validated)->except(['variants'])->toArray());

        // Hapus varian lama
        $product->variantOptions()->delete();
        $product->variantValues()->delete();
        $product->variantCombinations()->delete();

        $optionMap = [];
        $valueMap = [];

        // Kumpulkan semua nama opsi unik
        $optionNames = collect($validated['variants'] ?? [])
            ->flatMap(fn($variant) => collect($variant['options'])->map(function ($opt) {
                return explode(':', $opt)[0]; // Warna: Merah -> Warna
            }))
            ->unique()
            ->values();

        // Simpan opsi varian
        foreach ($optionNames as $optionName) {
            $option = $product->variantOptions()->create(['name' => $optionName]);
            $optionMap[$optionName] = $option;
        }

        // Simpan nilai varian
        foreach ($validated['variants'] ?? [] as $variant) {
            foreach ($variant['options'] as $opt) {
                [$key, $val] = array_map('trim', explode(':', $opt));
                if (!isset($optionMap[$key]))
                    continue;

                $variantValue = $optionMap[$key]->variantValues()->firstOrCreate(['value' => $val]);
                $valueMap["$key:$val"] = $variantValue->id;
            }
        }

        // Simpan kombinasi varian
        foreach ($validated['variants'] ?? [] as $variant) {
            $comb = new ProductVariantCombination();
            $comb->product_id = $product->id;
            $comb->stock = $variant['stock'];
            $comb->price = $variant['price'] ?? $product->price;
            $comb->discount_type = $variant['discount_type'] ?? null;
            $comb->discount_value = $variant['discount_value'] ?? 0;
            $comb->save();

            $variantValueIds = [];
            foreach ($variant['options'] as $opt) {
                if (isset($valueMap[$opt])) {
                    $comb->variantValues()->attach($valueMap[$opt]);
                    $variantValueIds[] = $valueMap[$opt];
                }
            }

            $comb->variant_value_ids = json_encode($variantValueIds);
            $comb->save();
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
