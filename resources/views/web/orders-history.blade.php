@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-title font-extrabold text-3xl text-gray-900 mb-8">Riwayat Pesanan Anda</h1>

        @if($orders->isEmpty())
            <div class="bg-white text-center py-20 rounded-3xl border border-gray-100 shadow-sm">
                <div class="text-4xl mb-4">📦</div>
                <p class="text-gray-500 text-sm font-semibold mb-4">Anda belum memiliki riwayat pesanan.</p>
                <a href="{{ route('menu.list') }}" class="bg-olive-500 hover:bg-olive-600 text-white px-6 py-2.5 rounded-full text-xs font-semibold shadow-md transition-all">Pesan Menu Sekarang</a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
                        <!-- Card Header -->
                        <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <span class="font-title font-bold text-lg text-gray-900">Order #DINDA-{{ $order->id }}</span>
                                <span class="text-xs text-gray-400 font-semibold block mt-0.5">Dipesan pada: {{ $order->created_at->format('d M Y, H:i') }}</span>
                            </div>
                            
                            <div class="flex gap-2">
                                <!-- Status Pembayaran Badge -->
                                @if($order->status_pembayaran === 'Dibayar')
                                    <span class="bg-emerald-50 text-emerald-700 font-bold text-xs py-1 px-3 rounded-full border border-emerald-200 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> {{ $order->status_pembayaran }}
                                    </span>
                                @elseif($order->status_pembayaran === 'Pending')
                                    <span class="bg-amber-50 text-amber-700 font-bold text-xs py-1 px-3 rounded-full border border-amber-200 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span> Menunggu Pembayaran
                                    </span>
                                @elseif($order->status_pembayaran === 'Kadaluwarsa')
                                    <span class="bg-rose-50 text-rose-700 font-bold text-xs py-1 px-3 rounded-full border border-rose-200 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Kadaluwarsa
                                    </span>
                                @else
                                    <span class="bg-rose-50 text-rose-700 font-bold text-xs py-1 px-3 rounded-full border border-rose-200 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Gagal
                                    </span>
                                @endif

                                <!-- Status Pesanan Badge -->
                                @if($order->status_pesanan === 'Selesai')
                                    <span class="bg-olive-50 text-olive-700 font-bold text-xs py-1 px-3 rounded-full border border-olive-200 flex items-center gap-1">
                                        ✓ {{ $order->status_pesanan }}
                                    </span>
                                @elseif($order->status_pesanan === 'Dibatalkan')
                                    <span class="bg-red-50 text-red-700 font-bold text-xs py-1 px-3 rounded-full border border-red-200 flex items-center gap-1">
                                        Dibatalkan
                                    </span>
                                @else
                                    <span class="bg-blue-50 text-blue-700 font-bold text-xs py-1 px-3 rounded-full border border-blue-200 flex items-center gap-1">
                                        ⚙️ {{ $order->status_pesanan }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 space-y-6">
                            <!-- Items List -->
                            <div class="space-y-4">
                                @foreach($order->orderItems as $item)
                                    <div class="flex items-center gap-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                            @if($item->product->gambar)
                                                <img src="{{ asset('images/' . $item->product->gambar) }}" class="w-full h-full object-cover" alt="{{ $item->product->nama_menu }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                                            @else
                                                <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold font-title text-sm">W</div>
                                            @endif
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-title font-bold text-base text-gray-900 leading-snug">{{ $item->product->nama_menu }}</h4>
                                            <p class="text-xs text-gray-400 font-semibold mt-0.5">{{ $item->qty }} porsi x Rp {{ number_format($item->product->harga, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-sm font-bold text-gray-700">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <hr class="border-gray-100">

                            <!-- Details (Shipping Date, Address, Notes) -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs font-semibold text-gray-500">
                                <div class="space-y-3">
                                    <div>
                                        <span class="text-gray-400 block mb-0.5">📅 Tanggal Pengiriman</span>
                                        <span class="text-gray-900 text-sm font-bold">{{ \Carbon\Carbon::parse($order->tanggal_pengiriman)->format('d M Y') }}</span>
                                    </div>
                                    @if($order->catatan)
                                        <div>
                                            <span class="text-gray-400 block mb-0.5">📝 Catatan Pesanan</span>
                                            <span class="text-gray-700 block italic font-normal">"{{ $order->catatan }}"</span>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <span class="text-gray-400 block mb-0.5">📍 Alamat Pengiriman</span>
                                    <span class="text-gray-850 font-normal leading-relaxed block text-sm">{{ $order->alamat }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="bg-gray-50/30 px-6 py-4 border-t border-gray-100 flex flex-wrap justify-between items-center gap-4">
                            <div>
                                <span class="text-xs text-gray-400 font-semibold block">Total Pembayaran</span>
                                <span class="font-title font-bold text-xl text-olive-500">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                            </div>

                            @if($order->status_pembayaran === 'Pending')
                                <a href="{{ route('orders.payment', $order->id) }}" class="bg-accent-500 hover:bg-accent-600 text-white font-semibold px-6 py-2.5 rounded-full text-xs shadow-md shadow-accent-500/10 hover:shadow-lg transition-all">
                                    💳 Bayar Sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
