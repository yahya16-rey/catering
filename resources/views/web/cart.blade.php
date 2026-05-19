@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-title font-extrabold text-3xl text-gray-900 mb-8">Keranjang Belanja</h1>

        @if(empty($cart))
            <div class="bg-white text-center py-20 rounded-3xl border border-gray-100 shadow-sm">
                <div class="text-4xl mb-4">🛒</div>
                <p class="text-gray-500 text-sm font-semibold mb-4">Keranjang belanja Anda masih kosong.</p>
                <a href="{{ route('menu.list') }}" class="bg-olive-500 hover:bg-olive-600 text-white px-6 py-2.5 rounded-full text-xs font-semibold shadow-md transition-all">Lihat Menu</a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <!-- Left: Item List -->
                <div class="lg:col-span-2 space-y-4">
                    @foreach($cart as $id => $item)
                        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm flex flex-col sm:flex-row items-center gap-6">
                            <div class="w-20 h-20 bg-gray-100 rounded-2xl overflow-hidden flex-shrink-0">
                                @if($item['image'])
                                    <img src="{{ asset('images/' . $item['image']) }}" class="w-full h-full object-cover" alt="{{ $item['name'] }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                                @else
                                    <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold font-title text-sm">W</div>
                                @endif
                            </div>
                            <div class="flex-grow text-center sm:text-left">
                                <span class="bg-olive-50 text-olive-600 font-bold text-[10px] py-1 px-2.5 rounded-full border border-olive-100/50 block w-fit mx-auto sm:mx-0 mb-2">{{ $item['kategori'] }}</span>
                                <h3 class="font-title font-bold text-lg text-gray-900">{{ $item['name'] }}</h3>
                                <p class="text-xs text-gray-400 font-semibold mt-1">🔥 {{ $item['kalori'] }} kkal | 💪 {{ $item['protein'] }}g Protein</p>
                                <p class="text-sm font-semibold text-gray-700 mt-2">Rp {{ number_format($item['price'], 0, ',', '.') }}</p>
                            </div>
                            
                            <!-- Qty & Delete -->
                            <div class="flex flex-col items-center sm:items-end gap-3 flex-shrink-0">
                                <div class="flex items-center border border-gray-200 rounded-full px-3 py-1 bg-white">
                                    <span class="text-xs text-gray-500 font-semibold mr-2">Qty:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $item['qty'] }}</span>
                                </div>
                                <a href="{{ route('cart.remove', $id) }}" class="text-xs text-red-500 hover:text-red-600 font-semibold hover:underline">Hapus</a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Right: Summary -->
                <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
                    <h2 class="font-title font-bold text-xl text-gray-900 mb-6">Ringkasan Pesanan</h2>
                    
                    @php
                        $subtotal = 0;
                        foreach($cart as $item) {
                            $subtotal += $item['price'] * $item['qty'];
                        }
                    @endphp

                    <div class="space-y-4 text-sm font-medium text-gray-500">
                        <div class="flex justify-between">
                            <span>Subtotal</span>
                            <span class="text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Biaya Pengiriman</span>
                            <span class="text-gray-900">Rp 0 (Gratis)</span>
                        </div>
                        <hr class="border-gray-100">
                        <div class="flex justify-between font-title font-bold text-lg text-gray-900">
                            <span>Total Pembayaran</span>
                            <span class="text-olive-500">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('checkout') }}" class="block w-full bg-olive-500 hover:bg-olive-600 text-white font-semibold py-3.5 px-6 rounded-full text-center text-sm shadow-md shadow-olive-500/10 hover:shadow-lg transition-all mt-8">Lanjutkan ke Checkout</a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection