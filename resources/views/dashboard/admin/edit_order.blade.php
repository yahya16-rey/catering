@extends('layouts.admin')

@section('admin_content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-1">Edit Status Pesanan</h1>
        <p class="text-xs text-gray-400 font-semibold">Update status pembayaran dan status pengiriman pesanan #{{ $order->id }}.</p>
    </div>
    <a href="{{ route('orders.index') }}" class="inline-flex items-center gap-2 text-xs text-gray-500 hover:text-olive-500 transition-colors font-semibold">
        👈 Kembali ke daftar pesanan
    </a>
</div>

<!-- Form Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
    
    <!-- Left: Status Form -->
    <div class="lg:col-span-2 bg-white p-8 sm:p-10 rounded-3xl border border-gray-100 shadow-sm">
        <h2 class="font-title font-bold text-lg text-gray-900 mb-6">⚙️ Pengaturan Status</h2>
        
        <form action="{{ route('orders.update', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Status Pembayaran -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Pembayaran</label>
                    <select name="status_pembayaran" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                        <option value="Pending" {{ old('status_pembayaran', $order->status_pembayaran) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Dibayar" {{ old('status_pembayaran', $order->status_pembayaran) == 'Dibayar' ? 'selected' : '' }}>Dibayar</option>
                        <option value="Kadaluwarsa" {{ old('status_pembayaran', $order->status_pembayaran) == 'Kadaluwarsa' ? 'selected' : '' }}>Kadaluwarsa</option>
                        <option value="Gagal" {{ old('status_pembayaran', $order->status_pembayaran) == 'Gagal' ? 'selected' : '' }}>Gagal</option>
                    </select>
                </div>

                <!-- Status Pengiriman/Pesanan -->
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Status Pesanan</label>
                    <select name="status_pesanan" required class="w-full bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3.5 text-sm focus:outline-none focus:border-olive-500 transition-colors">
                        <option value="Pending" {{ old('status_pesanan', $order->status_pesanan) == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Diproses" {{ old('status_pesanan', $order->status_pesanan) == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="Dikirim" {{ old('status_pesanan', $order->status_pesanan) == 'Dikirim' ? 'selected' : '' }}>Dikirim</option>
                        <option value="Selesai" {{ old('status_pesanan', $order->status_pesanan) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="Dibatalkan" {{ old('status_pesanan', $order->status_pesanan) == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>
            </div>

            <hr class="border-gray-100">

            <div class="flex gap-4">
                <button type="submit" class="bg-olive-500 hover:bg-olive-600 text-white font-semibold py-3 px-6 rounded-2xl text-xs shadow-md shadow-olive-500/10 hover:shadow-lg transition-all">Simpan Perubahan</button>
                <a href="{{ route('orders.index') }}" class="border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold py-3 px-6 rounded-2xl text-xs transition-all">Batalkan</a>
            </div>
        </form>
    </div>

    <!-- Right: Order Details Summary -->
    <div class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm">
        <h2 class="font-title font-bold text-lg text-gray-900 mb-6">📄 Rincian Pesanan</h2>
        
        <div class="space-y-4 text-xs">
            <div>
                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Customer</span>
                <span class="font-bold text-gray-900 block text-sm">{{ $order->user->name ?? 'Guest' }}</span>
                <span class="text-gray-500 font-semibold block">{{ $order->user->email ?? '' }}</span>
            </div>
            
            <hr class="border-gray-50">

            <div>
                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Menu Dipesan</span>
                <div class="space-y-1">
                    @foreach($order->orderItems as $item)
                        <div class="font-semibold text-gray-700">
                            ● {{ $item->product->nama_menu ?? 'Menu Terhapus' }} <span class="text-gray-400">(x{{ $item->qty }})</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <hr class="border-gray-50">

            <div>
                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Tanggal & Alamat Kirim</span>
                <span class="font-bold text-gray-900 block">📅 {{ date('d M Y', strtotime($order->tanggal_pengiriman)) }}</span>
                <span class="text-gray-500 leading-relaxed block mt-1">{{ $order->alamat }}</span>
            </div>

            <hr class="border-gray-50">

            <div>
                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Catatan Pelanggan</span>
                <span class="text-gray-600 italic block">{{ $order->catatan ?? '-' }}</span>
            </div>

            <hr class="border-gray-50">

            <div>
                <span class="text-gray-400 font-bold uppercase tracking-wider block mb-1">Total Pembayaran</span>
                <span class="font-title font-bold text-xl text-olive-500">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
