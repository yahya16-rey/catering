@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Step Indicators (Matching User Reference) -->
        <div class="flex justify-between items-center max-w-xl mx-auto mb-16 relative">
            <div class="absolute left-0 right-0 h-0.5 bg-gray-200 top-1/2 -translate-y-1/2 z-0"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-olive-500 text-white font-semibold flex items-center justify-center text-xs">1</span>
                <span class="text-xs font-bold text-gray-500 mt-2">Pilih Menu</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-olive-500 text-white font-semibold flex items-center justify-center text-xs">2</span>
                <span class="text-xs font-bold text-olive-500 mt-2">Checkout</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-semibold flex items-center justify-center text-xs">3</span>
                <span class="text-xs font-semibold text-gray-400 mt-2">Pembayaran</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-semibold flex items-center justify-center text-xs">4</span>
                <span class="text-xs font-semibold text-gray-400 mt-2">Selesai</span>
            </div>
        </div>

        <!-- Main Grid -->
        <form action="{{ route('order.submit') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Left: Form -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <h2 class="font-title font-bold text-xl text-gray-900 mb-6 flex items-center gap-2">
                            👤 Informasi Pengiriman
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nomor HP</label>
                                <input type="text" name="phone" placeholder="0812xxxx" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                            <textarea name="alamat" rows="4" placeholder="Masukkan alamat pengiriman secara detail..." required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors"></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Tanggal Pengiriman</label>
                                <input type="date" name="tanggal_pengiriman" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Catatan Tambahan</label>
                                <input type="text" name="catatan" placeholder="Opsional" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Secure Payment Banner -->
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🛡️</span>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900">Pembayaran Aman</h4>
                                <p class="text-xs text-gray-500">Pembayaran dienkripsi langsung oleh gerbang pembayaran Midtrans.</p>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-olive-600 bg-olive-50 px-3 py-1 rounded-full border border-olive-100">Midtrans Secure</span>
                    </div>
                </div>

                <!-- Right: Order Summary -->
                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                        <h3 class="font-title font-bold text-lg text-gray-900 mb-6">Ringkasan Pesanan</h3>
                        
                        <!-- List Cart Items -->
                        <div class="space-y-4 mb-6">
                            @php
                                $subtotal = 0;
                            @endphp
                            @foreach($cart as $id => $item)
                                @php
                                    $subtotal += $item['price'] * $item['qty'];
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0">
                                        @if($item['image'])
                                            <img src="{{ asset('images/' . $item['image']) }}" class="w-full h-full object-cover" alt="{{ $item['name'] }}">
                                        @else
                                            <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold text-xs">W</div>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-semibold text-xs text-gray-900 line-clamp-1">{{ $item['name'] }}</h4>
                                        <p class="text-[10px] text-gray-400 font-semibold mt-0.5">Qty: {{ $item['qty'] }} • Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 flex-shrink-0">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <hr class="border-gray-100 mb-6">

                        <!-- Pricing Summary -->
                        <div class="space-y-4 text-xs font-semibold text-gray-500 mb-8">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Pengiriman</span>
                                <span class="text-gray-900">Rp 0 (Gratis)</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Biaya Layanan AI</span>
                                <span class="text-emerald-600">Rp 0 (Promo)</span>
                            </div>
                            <hr class="border-gray-100">
                            <div class="flex justify-between font-title font-bold text-base text-gray-900">
                                <span>Total Pembayaran</span>
                                <span class="text-olive-500 text-lg">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-accent-500 hover:bg-accent-600 text-white font-semibold py-3.5 rounded-full text-center text-sm shadow-md shadow-accent-500/10 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                            🔒 Bayar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection