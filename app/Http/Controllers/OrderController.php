<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Submit Order (Checkout)
     */
    public function submit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_pengiriman' => 'required|date|after_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('menu.list')->with('errorMessage', 'Keranjang belanja kosong.');
        }

        DB::beginTransaction();
        try {
            // Calculate total price
            $totalHarga = 0;
            foreach ($cart as $item) {
                $totalHarga += $item['price'] * $item['qty'];
            }

            // Create Order
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_harga' => $totalHarga,
                'status_pesanan' => 'Pending',
                'status_pembayaran' => 'Pending',
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'alamat' => $request->alamat,
                'catatan' => $request->catatan,
            ]);

            // Create Order Items and update product stock
            $items = [];
            foreach ($cart as $productId => $details) {
                $product = Product::findOrFail($productId);
                
                // Enforce minimum 5 portions check
                if ($details['qty'] < 5) {
                    throw new \Exception("Pemesanan minimal untuk menu " . $product->nama_menu . " adalah 5 porsi.");
                }

                // Deduct stock
                if ($product->stok < $details['qty']) {
                    throw new \Exception("Stok tidak mencukupi untuk menu: " . $product->nama_menu);
                }
                $product->stok -= $details['qty'];
                $product->save();

                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'qty' => $details['qty'],
                    'subtotal' => $details['price'] * $details['qty'],
                ]);
                $items[] = $orderItem;
            }

            // Get Midtrans Snap Token
            $snapToken = $this->midtransService->getSnapToken($order, Auth::user(), $items);

            if (!$snapToken) {
                throw new \Exception("Gagal menghubungi gerbang pembayaran Midtrans. Silakan periksa koneksi internet Anda atau hubungi admin.");
            }

            // Create Payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'status' => 'Pending',
                'snap_token' => $snapToken,
            ]);

            DB::commit();

            // Clear Cart Session
            session()->forget('cart');

            return view('web.checkout-payment', [
                'order' => $order,
                'payment' => $payment,
                'snapToken' => $snapToken,
                'title' => 'Pembayaran Pesanan'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('errorMessage', $e->getMessage());
        }
    }

    /**
     * Admin Order List
     */
    public function index()
    {
        $orders = Order::with('user', 'orderItems.product')->latest()->paginate(10);
        return view('dashboard.admin.orders', compact('orders'));
    }

    /**
     * Admin Edit Order
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('dashboard.admin.edit_order', compact('order'));
    }

    /**
     * Admin Update Order Status
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pesanan' => 'required|in:Pending,Diproses,Dikirim,Selesai,Dibatalkan',
            'status_pembayaran' => 'required|in:Pending,Dibayar,Kadaluwarsa,Gagal',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status_pesanan' => $request->status_pesanan,
            'status_pembayaran' => $request->status_pembayaran,
        ]);

        return redirect()->route('orders.index')->with('successMessage', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Admin Delete Order
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->route('orders.index')->with('successMessage', 'Pesanan berhasil dihapus.');
    }

    /**
     * Customer Order History
     */
    public function history()
    {
        $orders = Order::with('orderItems.product', 'payment')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(5);

        return view('web.orders-history', [
            'orders' => $orders,
            'title' => 'Riwayat Pesanan'
        ]);
    }

    /**
     * Customer Re-pay Order
     */
    public function paymentPage($id)
    {
        $order = Order::with('payment', 'orderItems.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        if ($order->status_pembayaran !== 'Pending') {
            return redirect()->route('orders.history')->with('errorMessage', 'Pesanan ini sudah dibayar atau tidak dapat dibayar lagi.');
        }

        $payment = $order->payment;
        $snapToken = $payment ? $payment->snap_token : null;

        // If payment doesn't exist or doesn't have a snap token, generate one
        if (!$payment || !$snapToken) {
            try {
                $snapToken = $this->midtransService->getSnapToken($order, Auth::user(), $order->orderItems);
                if (!$snapToken) {
                    throw new \Exception("Gagal menghubungi gerbang pembayaran Midtrans. Silakan hubungi admin.");
                }

                if (!$payment) {
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'status' => 'Pending',
                        'snap_token' => $snapToken,
                    ]);
                } else {
                    $payment->update([
                        'snap_token' => $snapToken,
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->route('orders.history')->with('errorMessage', $e->getMessage());
            }
        }

        return view('web.checkout-payment', [
            'order' => $order,
            'payment' => $payment,
            'snapToken' => $snapToken,
            'title' => 'Pembayaran Pesanan'
        ]);
    }
}
