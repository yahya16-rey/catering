@extends('layouts.app')

@section('content')
<!-- Header Section -->
<section class="bg-white border-b border-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-title font-extrabold text-3xl text-gray-900 mb-2">Pilihan Menu Catering</h1>
        <p class="text-gray-500 text-sm">Temukan hidangan lezat dan bernutrisi tinggi yang disiapkan segar untuk Anda.</p>
    </div>
</section>

<!-- Main Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Search and Filters -->
        <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row justify-between gap-6">
            <!-- Search Form -->
            <form action="{{ route('menu.list') }}" method="GET" class="w-full md:w-1/3 relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari menu catering..." class="w-full bg-gray-50 border border-gray-200 rounded-full px-5 py-3 pl-12 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </form>

            <!-- Category Filters -->
            <div class="flex flex-wrap gap-2.5 items-center">
                <a href="{{ route('menu.list', ['category' => '', 'search' => request('search')]) }}" class="px-5 py-2.5 rounded-full text-xs font-semibold transition-all border {{ !request('category') ? 'bg-olive-500 text-white border-olive-500' : 'bg-white text-gray-600 border-gray-200 hover:border-olive-500' }}">Semua</a>
                <a href="{{ route('menu.list', ['category' => 'Corporate', 'search' => request('search')]) }}" class="px-5 py-2.5 rounded-full text-xs font-semibold transition-all border {{ request('category') == 'Corporate' ? 'bg-olive-500 text-white border-olive-500' : 'bg-white text-gray-600 border-gray-200 hover:border-olive-500' }}">Corporate</a>
                <a href="{{ route('menu.list', ['category' => 'Event', 'search' => request('search')]) }}" class="px-5 py-2.5 rounded-full text-xs font-semibold transition-all border {{ request('category') == 'Event' ? 'bg-olive-500 text-white border-olive-500' : 'bg-white text-gray-600 border-gray-200 hover:border-olive-500' }}">Event</a>
                <a href="{{ route('menu.list', ['category' => 'Personal', 'search' => request('search')]) }}" class="px-5 py-2.5 rounded-full text-xs font-semibold transition-all border {{ request('category') == 'Personal' ? 'bg-olive-500 text-white border-olive-500' : 'bg-white text-gray-600 border-gray-200 hover:border-olive-500' }}">Personal</a>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->isEmpty())
            <div class="bg-white text-center py-20 rounded-3xl border border-gray-100 shadow-sm">
                <p class="text-gray-500 text-sm font-semibold">Menu yang Anda cari tidak ditemukan.</p>
                <a href="{{ route('menu.list') }}" class="text-olive-500 hover:underline text-xs font-bold mt-2 inline-block">Kembali ke semua menu</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @foreach($products as $product)
                    <div class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100 flex flex-col h-full group">
                        <div class="relative overflow-hidden aspect-video bg-gray-100">
                            @if($product->gambar)
                                <img src="{{ asset('images/' . $product->gambar) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $product->nama_menu }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                            @else
                                <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold font-title">Widia Catering</div>
                            @endif
                            <span class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm text-gray-900 font-semibold text-xs py-1 px-3 rounded-full shadow-sm">{{ $product->kategori }}</span>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="font-title font-bold text-lg text-gray-900 group-hover:text-olive-500 transition-colors">{{ $product->nama_menu }}</h3>
                                <div class="flex items-center gap-1 text-amber-500">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-xs font-bold text-gray-900">{{ number_format($product->rating, 1) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 line-clamp-2 mb-4">{{ $product->deskripsi }}</p>
                            
                            <div class="flex gap-4 text-xs font-semibold text-gray-500 mb-6 bg-gray-50 p-2.5 rounded-xl">
                                <div>🔥 {{ $product->kalori }} kkal</div>
                                <div>💪 {{ $product->protein }}g Protein</div>
                            </div>

                            <div class="flex justify-between items-center mt-auto">
                                <span class="font-title font-bold text-lg text-gray-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                <a href="{{ route('menu.detail', $product->id) }}" class="border border-gray-200 hover:border-olive-500 text-gray-700 hover:text-white hover:bg-olive-500 px-4 py-2 rounded-full text-xs font-semibold transition-all">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-12">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
