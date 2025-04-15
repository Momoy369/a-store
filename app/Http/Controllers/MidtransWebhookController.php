<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();

        // Log webhook untuk debugging
        Log::info('Midtrans Webhook:', $payload);

        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;

        $order = Order::where('id', $orderId)->first();

        if ($order) {
            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                $order->status = 'paid';
            } elseif ($transactionStatus === 'deny' || $transactionStatus === 'expire' || $transactionStatus === 'cancel') {
                $order->status = 'failed';
            } else {
                $order->status = $transactionStatus;
            }

            $order->save();
        }

        return response()->json(['message' => 'OK']);
    }
}
