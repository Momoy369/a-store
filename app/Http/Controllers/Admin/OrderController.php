<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

use Barryvdh\DomPDF\Facade\Pdf as PDF;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items.product');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Order::STATUSES),
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'Status pesanan diperbarui!');
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('store.orders-history', compact('orders'));
    }

    public function exportPdf(Request $request)
    {
        $orders = Order::when($request->status, function ($query) use ($request) {
            return $query->where('status', $request->status);
        })->get();

        $pdf = PDF::loadView('admin.orders.pdf', compact('orders'));
        return $pdf->download('daftar-pesanan.pdf');
    }

}