@extends('layouts.admin')

@section('admin_content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-1">Edit Menu Catering</h1>
        <p class="text-xs text-gray-400 font-semibold">Update data hidangan katering Anda.</p>
    </div>
    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-xs text-gray-500 hover:text-olive-500 transition-colors font-semibold">
        👈 Kembali ke daftar menu
    </a>
</div>

<!-- Form Card -->
<div class="bg-white p-8 sm:p-10 rounded-3xl border border-gray-100 shadow-sm max-w-3xl">
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Nama Menu -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Nama Menu</label>
                <input type="text" name="nama_menu" value="{{ old('nama_menu', $product->nama_menu) }}" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kategori</label>
                <select name="kategori" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                    <option value="">Pilih Kategori...</option>
                    <option value="Corporate" {{ old('kategori', $product->kategori) == 'Corporate' ? 'selected' : '' }}>Corporate</option>
                    <option value="Event" {{ old('kategori', $product->kategori) == 'Event' ? 'selected' : '' }}>Event</option>
                    <option value="Personal" {{ old('kategori', $product->kategori) == 'Personal' ? 'selected' : '' }}>Personal</option>
                </select>
            </div>
        </div>

        <!-- Deskripsi -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Deskripsi Menu</label>
            <textarea name="deskripsi" rows="4" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">{{ old('deskripsi', $product->deskripsi) }}</textarea>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Harga -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Harga (Rp)</label>
                <input type="number" name="harga" value="{{ old('harga', $product->harga) }}" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
            </div>

            <!-- Stok -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Stok</label>
                <input type="number" name="stok" value="{{ old('stok', $product->stok) }}" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <!-- Kalori -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Kalori (Kkal)</label>
                <input type="number" name="kalori" value="{{ old('kalori', $product->kalori) }}" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
            </div>

            <!-- Protein -->
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Protein (g)</label>
                <input type="number" name="protein" value="{{ old('protein', $product->protein) }}" min="1" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
            </div>
        </div>

        <!-- Current image preview -->
        @if($product->gambar)
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Gambar Saat Ini</label>
                <div class="w-32 h-32 bg-gray-100 rounded-2xl overflow-hidden border border-gray-200">
                    <img src="{{ asset('images/' . $product->gambar) }}" class="w-full h-full object-cover" alt="{{ $product->nama_menu }}">
                </div>
            </div>
        @endif

        <!-- Image Upload -->
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Ganti Gambar Hidangan</label>
            <input type="file" name="image" accept="image/*" class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3 text-sm focus:outline-none focus:border-olive-500 transition-colors">
            <span class="text-[10px] text-gray-400 font-semibold block mt-1">Biarkan kosong jika tidak ingin mengubah gambar.</span>
        </div>

        <!-- Is Available -->
        <div class="flex items-center">
            <input type="checkbox" id="is_available" name="is_available" value="1" {{ old('is_available', $product->is_available) ? 'checked' : '' }} class="w-4 h-4 text-olive-500 border-gray-300 rounded focus:ring-olive-500">
            <label for="is_available" class="ml-2 text-xs font-bold text-gray-500 uppercase tracking-wider">Tersedia di Katalog</label>
        </div>

        <hr class="border-gray-100">

        <!-- Buttons -->
        <div class="flex gap-4 pt-2">
            <button type="submit" class="bg-olive-500 hover:bg-olive-600 text-white font-semibold py-3 px-6 rounded-2xl text-xs shadow-md shadow-olive-500/10 hover:shadow-lg transition-all">Perbarui Menu</button>
            <a href="{{ route('products.index') }}" class="border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold py-3 px-6 rounded-2xl text-xs transition-all">Batalkan</a>
        </div>
    </form>
</div>
@endsection