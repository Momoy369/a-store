<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Midtrans\Snap;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart || count($cart) == 0) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong!');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
        ]);

        // Validasi stok sebelum lanjut ke pemesanan
        foreach ($cart as $id => $item) {
            $product = Product::find($id);

            // Jika kamu menggunakan varian, gunakan kode ini:
            $product = Variant::find($id);

            if ($product && $product->stock < $item['quantity']) {
                // Jika stok kurang dari yang dibutuhkan, kembalikan ke halaman cart
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $product->name . ' tidak mencukupi.');
            }
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'invoice_number' => 'INV-' . strtoupper(Str::random(10)),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'total_price' => $total,
        ]);

        foreach ($cart as $id => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $id,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Mengurangi stok produk setelah berhasil membuat order
            $product = Product::find($id);
            if ($product) {
                $product->stock -= $item['quantity'];
                $product->save();
            }
        }

        // Midtrans Snap Integration
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->invoice_number,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        $order->payment_url = $snapToken;
        $order->save();

        session()->forget('cart');

        return redirect("https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}");
    }


}