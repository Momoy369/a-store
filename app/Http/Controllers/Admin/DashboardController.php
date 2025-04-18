<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();

        $productsPerCategory = Category::withCount('products')->get();
        $latestProducts = Product::latest()->take(5)->get();

        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');
        $recentOrders = Order::latest()->take(5)->get();
        $orderStatuses = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        $lowStockCount = Product::with('variants')
            ->whereHas('variants', function ($query) {
                $query->where('stock', '<', 5);
            })
            ->count();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'productsPerCategory',
            'latestProducts',
            'totalOrders',
            'totalRevenue',
            'recentOrders',
            'orderStatuses',
            'lowStockCount',
        ));
    }

    public function lowStock()
    {
        // Ambil produk dan varian dengan stok rendah (di bawah 5)
        $lowStockProducts = Product::with('variants')
            ->whereHas('variants', function ($query) {
                $query->where('stock', '<', 5);
            })
            ->get();

        return view('admin.low-stock', compact('lowStockProducts'));
    }
}
