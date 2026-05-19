<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // General Stats
        $totalRevenue = Order::where('status_pembayaran', 'Dibayar')->sum('total_harga');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $stokMenipis = Product::where('stok', '<', 10)->count();

        // Top Selling Menus
        $topMenus = OrderItem::select('product_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as revenue'))
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status_pembayaran', 'Dibayar')
            ->groupBy('product_id')
            ->orderBy('total_qty', 'desc')
            ->with('product')
            ->take(5)
            ->get();

        // Loyal Customers
        $loyalCustomers = Order::select('user_id', DB::raw('COUNT(id) as total_orders'), DB::raw('SUM(total_harga) as total_spent'))
            ->where('status_pembayaran', 'Dibayar')
            ->groupBy('user_id')
            ->orderBy('total_spent', 'desc')
            ->with('user')
            ->take(5)
            ->get();

        // Sales Chart Data (Last 7 Days)
        $salesData = Order::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_harga) as total'))
            ->where('status_pembayaran', 'Dibayar')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = $salesData->pluck('date')->map(function ($date) {
            return date('d M', strtotime($date));
        })->toArray();

        $chartData = $salesData->pluck('total')->toArray();

        // If data is empty, provide mock data for clean UI rendering
        if (empty($chartLabels)) {
            $chartLabels = [
                now()->subDays(4)->format('d M'),
                now()->subDays(3)->format('d M'),
                now()->subDays(2)->format('d M'),
                now()->subDays(1)->format('d M'),
                now()->format('d M')
            ];
            $chartData = [1200000, 2450000, 850000, 3100000, $totalRevenue ?: 1500000];
        }

        return view('dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'stokMenipis',
            'topMenus',
            'loyalCustomers',
            'chartLabels',
            'chartData'
        ));
    }
}
