@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <a href="{{ route('menu.list') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-olive-500 transition-colors mb-8">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke katalog menu
        </a>

        <!-- Detail Grid -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 sm:p-10 grid grid-cols-1 md:grid-cols-2 gap-12">
            <!-- Left: Image -->
            <div class="relative overflow-hidden rounded-2xl bg-gray-100 aspect-square">
                @if($product->gambar)
                    <img src="{{ asset('images/' . $product->gambar) }}" class="w-full h-full object-cover" alt="{{ $product->nama_menu }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                @else
                    <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold font-title text-2xl">Dinda Catering</div>
                @endif
            </div>

            <!-- Right: Info -->
            <div class="flex flex-col">
                <span class="w-fit bg-olive-50 text-olive-600 font-bold text-xs py-1.5 px-3 rounded-full border border-olive-100/50 mb-4">{{ $product->kategori }}</span>
                <h1 class="font-title font-extrabold text-3xl text-gray-900 mb-2">{{ $product->nama_menu }}</h1>
                
                <!-- Rating -->
                <div class="flex items-center gap-1.5 mb-6 text-amber-500">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <span class="text-sm font-bold text-gray-900">{{ number_format($product->rating, 1) }} / 5.0</span>
                    <span class="text-xs text-gray-400 font-semibold">• {{ rand(10, 45) }} ulasan pelanggan</span>
                </div>

                <!-- Price -->
                <p class="font-title font-bold text-3xl text-gray-900 mb-6">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>

                <hr class="border-gray-100 mb-6">

                <!-- Description -->
                <h3 class="text-sm font-bold text-gray-900 mb-2">Deskripsi Hidangan</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-6">{{ $product->deskripsi }}</p>

                <!-- Nutrition Info -->
                <h3 class="text-sm font-bold text-gray-900 mb-2">Informasi Nutrisi</h3>
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 block">Kalori</span>
                        <span class="font-title font-bold text-lg text-gray-900">🔥 {{ $product->kalori }} kkal</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-xs font-semibold text-gray-500 block">Protein</span>
                        <span class="font-title font-bold text-lg text-gray-900">💪 {{ $product->protein }}g</span>
                    </div>
                </div>

                <!-- Stok and Order Form -->
                <div class="mt-auto">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm text-gray-500 font-semibold">Ketersediaan Stok</span>
                        @if($product->is_available && $product->stok >= 5)
                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">Tersedia ({{ $product->stok }} item)</span>
                        @else
                            <span class="text-xs font-bold text-red-600 bg-red-50 px-3 py-1 rounded-full border border-red-100">Habis / Kurang dari Min. Order</span>
                        @endif
                    </div>

                    @if($product->is_available && $product->stok >= 5)
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex flex-col gap-2">
                            @csrf
                            <div class="flex gap-4">
                                <!-- Qty Selector -->
                                <div class="flex items-center border border-gray-200 rounded-full px-4 bg-white">
                                    <button type="button" onclick="decrementQty()" class="text-gray-500 hover:text-olive-500 font-bold p-1">-</button>
                                    <input type="number" id="qty-input" name="qty" value="5" min="5" max="{{ $product->stok }}" class="w-12 text-center text-sm font-bold border-none focus:outline-none focus:ring-0 bg-transparent">
                                    <button type="button" onclick="incrementQty()" class="text-gray-500 hover:text-olive-500 font-bold p-1">+</button>
                                </div>
                                <!-- Submit -->
                                <button type="submit" class="flex-grow bg-olive-500 hover:bg-olive-600 text-white font-semibold py-3 px-6 rounded-full shadow-md shadow-olive-500/10 hover:shadow-lg transition-all text-center text-sm">Tambahkan ke Keranjang</button>
                            </div>
                            <span class="text-[10px] text-gray-400 font-semibold block mt-1">* Minimal pemesanan catering adalah 5 porsi.</span>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const qtyInput = document.getElementById('qty-input');
    const maxStock = {{ $product->stok }};

    function incrementQty() {
        let val = parseInt(qtyInput.value);
        if (val < maxStock) {
            qtyInput.value = val + 1;
        }
    }

    function decrementQty() {
        let val = parseInt(qtyInput.value);
        if (val > 5) {
            qtyInput.value = val - 1;
        }
    }
</script>
@endsection