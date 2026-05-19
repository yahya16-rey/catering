<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Midtrans Redirect Callback Finish Route
     */
    public function finish(Request $request)
    {
        $orderIdInput = $request->query('order_id');
        $statusCode = $request->query('status_code');
        $transactionStatus = $request->query('transaction_status');

        // Parse order id from WIDIA-X-TIME
        $orderId = null;
        if ($orderIdInput) {
            $parts = explode('-', $orderIdInput);
            if (isset($parts[1])) {
                $orderId = $parts[1];
            }
        }

        $order = null;
        if ($orderId) {
            $order = Order::find($orderId);
            if ($order && $transactionStatus) {
                // Determine payment and order statuses based on transaction_status
                $statusMap = [
                    'capture' => ['pembayaran' => 'Dibayar', 'pesanan' => 'Diproses', 'payment_status' => 'Success'],
                    'settlement' => ['pembayaran' => 'Dibayar', 'pesanan' => 'Diproses', 'payment_status' => 'Success'],
                    'pending' => ['pembayaran' => 'Pending', 'pesanan' => 'Pending', 'payment_status' => 'Pending'],
                    'deny' => ['pembayaran' => 'Gagal', 'pesanan' => 'Dibatalkan', 'payment_status' => 'Failure'],
                    'expire' => ['pembayaran' => 'Kadaluwarsa', 'pesanan' => 'Dibatalkan', 'payment_status' => 'Failure'],
                    'cancel' => ['pembayaran' => 'Gagal', 'pesanan' => 'Dibatalkan', 'payment_status' => 'Failure'],
                ];

                $status = $statusMap[$transactionStatus] ?? ['pembayaran' => 'Pending', 'pesanan' => 'Pending', 'payment_status' => 'Pending'];

                $order->update([
                    'status_pembayaran' => $status['pembayaran'],
                    'status_pesanan' => $status['pesanan'],
                ]);

                if ($order->payment) {
                    $order->payment->update([
                        'status' => $status['payment_status'],
                        'transaction_id' => $request->query('transaction_id')
                    ]);
                }
            }
        }

        return view('web.checkout-finish', [
            'order' => $order,
            'status' => $transactionStatus,
            'title' => 'Status Pembayaran'
        ]);
    }

    /**
     * Midtrans Notification Webhook
     */
    public function webhook(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::warning('Midtrans Webhook: Invalid Signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
        }

        $orderIdInput = $request->order_id;
        $parts = explode('-', $orderIdInput);
        $orderId = $parts[1] ?? null;

        $order = Order::find($orderId);
        if (!$order) {
            return response()->json(['status' => 'error', 'message' => 'Order not found'], 44);
        }

        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        $statusMap = [
            'capture' => ['pembayaran' => 'Dibayar', 'pesanan' => 'Diproses', 'payment_status' => 'Success'],
            'settlement' => ['pembayaran' => 'Dibayar', 'pesanan' => 'Diproses', 'payment_status' => 'Success'],
            'pending' => ['pembayaran' => 'Pending', 'pesanan' => 'Pending', 'payment_status' => 'Pending'],
            'deny' => ['pembayaran' => 'Gagal', 'pesanan' => 'Dibatalkan', 'payment_status' => 'Failure'],
            'expire' => ['pembayaran' => 'Kadaluwarsa', 'pesanan' => 'Dibatalkan', 'payment_status' => 'Failure'],
            'cancel' => ['pembayaran' => 'Gagal', 'pesanan' => 'Dibatalkan', 'payment_status' => 'Failure'],
        ];

        $status = $statusMap[$transactionStatus] ?? ['pembayaran' => 'Pending', 'pesanan' => 'Pending', 'payment_status' => 'Pending'];

        $order->update([
            'status_pembayaran' => $status['pembayaran'],
            'status_pesanan' => $status['pesanan'],
        ]);

        if ($order->payment) {
            $order->payment->update([
                'status' => $status['payment_status'],
                'metode' => $type,
                'transaction_id' => $request->transaction_id
            ]);
        }

        return response()->json(['status' => 'success']);
    }
}
