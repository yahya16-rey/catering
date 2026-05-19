@extends('layouts.admin')

@section('admin_content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-1">Data Menu Catering</h1>
        <p class="text-xs text-gray-400 font-semibold">Kelola menu makanan, deskripsi, harga, stok, dan nutrisi makanan.</p>
    </div>
    <a href="{{ route('products.create') }}" class="bg-olive-500 hover:bg-olive-600 text-white font-semibold py-2.5 px-5 rounded-2xl text-xs shadow-md shadow-olive-500/10 hover:shadow-lg transition-all flex items-center gap-1.5">
        ➕ Tambah Menu Baru
    </a>
</div>

<!-- Search Filter -->
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <form action="{{ route('products.index') }}" method="GET" class="relative max-w-sm">
        <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Cari menu atau kategori..." class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2 pl-9 text-xs focus:outline-none focus:border-olive-500 transition-colors">
        <span class="absolute left-3 top-2.5 text-gray-400 text-xs">🔍</span>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-gray-500 font-bold uppercase tracking-wider">
                    <th class="p-5">No</th>
                    <th class="p-5">Gambar</th>
                    <th class="p-5">Nama Menu</th>
                    <th class="p-5">Kategori</th>
                    <th class="p-5">Harga</th>
                    <th class="p-5 text-center">Stok</th>
                    <th class="p-5 text-center">Nutrisi (Kalori/Prot)</th>
                    <th class="p-5 text-center">Status</th>
                    <th class="p-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                @forelse($products as $index => $product)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-5 text-gray-400 font-semibold">{{ $products->firstItem() + $index }}</td>
                        <td class="p-5">
                            <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden">
                                @if($product->gambar)
                                    <img src="{{ asset('images/' . $product->gambar) }}" class="w-full h-full object-cover" alt="{{ $product->nama_menu }}" onerror="this.src='https://images.unsplash.com/photo-1546069901-ba9599a7e63c?q=80&w=600&auto=format&fit=crop'">
                                @else
                                    <div class="w-full h-full bg-olive-100 flex items-center justify-center text-olive-500 font-bold text-[10px]">W</div>
                                @endif
                            </div>
                        </td>
                        <td class="p-5">
                            <span class="font-bold text-gray-900 block">{{ $product->nama_menu }}</span>
                            <span class="text-[10px] text-gray-400 line-clamp-1 max-w-xs">{{ $product->deskripsi }}</span>
                        </td>
                        <td class="p-5">
                            <span class="bg-gray-100 text-gray-600 font-bold text-[10px] py-1 px-2.5 rounded-full">{{ $product->kategori }}</span>
                        </td>
                        <td class="p-5 font-bold text-gray-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td class="p-5 text-center font-bold text-gray-900">{{ $product->stok }}</td>
                        <td class="p-5 text-center text-[10px] text-gray-500 font-semibold">
                            🔥 {{ $product->kalori }} kkal <br>
                            💪 {{ $product->protein }}g Protein
                        </td>
                        <td class="p-5 text-center">
                            @if($product->is_available && $product->stok > 0)
                                <span class="bg-emerald-50 text-emerald-600 font-bold text-[10px] py-1 px-2.5 rounded-full border border-emerald-100">Tersedia</span>
                            @else
                                <span class="bg-red-50 text-red-600 font-bold text-[10px] py-1 px-2.5 rounded-full border border-red-100">Habis</span>
                            @endif
                        </td>
                        <td class="p-5 text-right flex justify-end gap-2 items-center min-h-[70px]">
                            <a href="{{ route('products.edit', $product->id) }}" class="p-2 rounded-xl bg-gray-50 hover:bg-olive-50 text-gray-500 hover:text-olive-600 transition-all font-bold">Edit</a>
                            
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 transition-all font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="p-10 text-center text-gray-400">Belum ada data menu catering.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="p-5 border-t border-gray-100 bg-gray-50">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection