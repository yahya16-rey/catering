@extends('layouts.admin')

@section('admin_content')
<!-- Page Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-1">Dashboard Analytics</h1>
        <p class="text-xs text-gray-400 font-semibold">Selamat datang di sistem manajemen katering kecerdasan buatan Dinda Catering.</p>
    </div>
</div>

<!-- Stats Overview Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Card Revenue -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan</span>
            <span class="p-2 rounded-xl bg-olive-50 text-olive-500 text-sm">💰</span>
        </div>
        <h3 class="font-title font-extrabold text-2xl text-gray-900 mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
        <p class="text-[10px] text-emerald-600 font-bold">● Transaksi berhasil</p>
    </div>

    <!-- Card Orders -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pesanan</span>
            <span class="p-2 rounded-xl bg-blue-50 text-blue-500 text-sm">📦</span>
        </div>
        <h3 class="font-title font-extrabold text-2xl text-gray-900 mb-1">{{ $totalOrders }}</h3>
        <p class="text-[10px] text-gray-400 font-semibold">Dari semua status</p>
    </div>

    <!-- Card Menus -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menu Aktif</span>
            <span class="p-2 rounded-xl bg-amber-50 text-amber-500 text-sm">🍲</span>
        </div>
        <h3 class="font-title font-extrabold text-2xl text-gray-900 mb-1">{{ $totalProducts }}</h3>
        <p class="text-[10px] text-gray-400 font-semibold">Tersedia di katalog</p>
    </div>

    <!-- Card Low Stock -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Stok Menipis</span>
            <span class="p-2 rounded-xl bg-red-50 text-red-500 text-sm">⚠️</span>
        </div>
        <h3 class="font-title font-extrabold text-2xl text-gray-900 mb-1">{{ $stokMenipis }}</h3>
        <p class="text-[10px] text-red-600 font-bold">Perlu re-stock segera</p>
    </div>
</div>

<!-- Sales Chart Section -->
<div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm mb-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-title font-bold text-lg text-gray-900 mb-1">Tren Penjualan</h3>
            <p class="text-xs text-gray-400 font-semibold">Grafik pendapatan katering dalam beberapa hari terakhir.</p>
        </div>
    </div>
    
    <!-- ChartJS Canvas wrapper -->
    <div class="h-80 w-full">
        <canvas id="salesChart" class="w-full h-full"></canvas>
    </div>
</div>

<!-- Two Columns Details Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    
    <!-- Top Selling Menus -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <h3 class="font-title font-bold text-base text-gray-900 mb-4">🍲 Menu Terlaris</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 text-gray-400 font-bold uppercase tracking-wider">
                        <th class="pb-3">Nama Menu</th>
                        <th class="pb-3 text-center">Jumlah Terjual</th>
                        <th class="pb-3 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($topMenus as $menu)
                        <tr>
                            <td class="py-3 flex items-center gap-2">
                                <span class="font-bold text-gray-900">{{ $menu->product->nama_menu ?? 'Menu Terhapus' }}</span>
                            </td>
                            <td class="py-3 text-center text-gray-900">{{ $menu->total_qty }} porsi</td>
                            <td class="py-3 text-right text-olive-500 font-bold">Rp {{ number_format($menu->revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-400">Belum ada menu terjual.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Loyal Customers -->
    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
        <h3 class="font-title font-bold text-base text-gray-900 mb-4">👤 Pelanggan Setia</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-gray-50 text-gray-400 font-bold uppercase tracking-wider">
                        <th class="pb-3">Customer</th>
                        <th class="pb-3 text-center">Total Order</th>
                        <th class="pb-3 text-right">Total Belanja</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700 font-medium">
                    @forelse($loyalCustomers as $customer)
                        <tr>
                            <td class="py-3">
                                <span class="font-bold text-gray-900 block">{{ $customer->user->name ?? 'Guest' }}</span>
                                <span class="text-[10px] text-gray-400">{{ $customer->user->email ?? '' }}</span>
                            </td>
                            <td class="py-3 text-center text-gray-900">{{ $customer->total_orders }} kali</td>
                            <td class="py-3 text-right text-olive-500 font-bold">Rp {{ number_format($customer->total_spent, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-4 text-center text-gray-400">Belum ada pelanggan melakukan pemesanan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ChartJS CDN and setup -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Gradient fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(94, 111, 87, 0.25)');
        gradient.addColorStop(1, 'rgba(94, 111, 87, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($chartData),
                    borderColor: '#5E6F57',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#5E6F57',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10,
                                weight: 'semibold'
                            },
                            color: '#9CA3AF'
                        }
                    },
                    y: {
                        grid: {
                            color: '#F3F4F6'
                        },
                        ticks: {
                            font: {
                                size: 10,
                                weight: 'semibold'
                            },
                            color: '#9CA3AF',
                            callback: function(value) {
                                return 'Rp ' + (value/1000).toLocaleString() + 'k';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
