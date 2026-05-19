@extends('layouts.admin')

@section('admin_content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-1">Rekap Pemesanan</h1>
        <p class="text-xs text-gray-400 font-semibold">Pantau, proses, dan ekspor laporan transaksi katering pelanggan.</p>
    </div>
</div>

<!-- Filters & Excel Export -->
<div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm mb-6 flex flex-col md:flex-row justify-between items-center gap-4">
    <form action="{{ route('orders.export') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <select name="month" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-olive-500">
            @for ($i = 1; $i <= 12; $i++)
                <option value="{{ sprintf('%02d', $i) }}" {{ request('month', now()->format('m')) == sprintf('%02d', $i) ? 'selected' : '' }}>
                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                </option>
            @endfor
        </select>

        <select name="year" class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-xs focus:outline-none focus:border-olive-500">
            @for ($y = now()->year; $y >= 2020; $y--)
                <option value="{{ $y }}" {{ request('year', now()->format('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>

        <button type="submit" class="bg-olive-500 hover:bg-olive-600 text-white font-semibold py-2.5 px-5 rounded-xl text-xs shadow-md transition-all flex items-center gap-1.5">
            📊 Export Excel
        </button>
    </form>
</div>

<!-- Table -->
<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-gray-100 bg-gray-50 text-gray-500 font-bold uppercase tracking-wider">
                    <th class="p-5">ID Order</th>
                    <th class="p-5">Pelanggan</th>
                    <th class="p-5">Menu Dipesan</th>
                    <th class="p-5">Total Harga</th>
                    <th class="p-5 text-center">Status Bayar</th>
                    <th class="p-5 text-center">Status Pesan</th>
                    <th class="p-5">Tgl Kirim / Alamat</th>
                    <th class="p-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700 font-medium">
                @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="p-5 font-bold text-gray-900">#{{ $order->id }}</td>
                        <td class="p-5">
                            <span class="font-bold text-gray-900 block">{{ $order->user->name ?? 'Guest' }}</span>
                            <span class="text-[10px] text-gray-400 block">{{ $order->user->email ?? '-' }}</span>
                            <span class="text-[10px] text-gray-400">{{ $order->alamat ? 'Telp: ' : '' }}</span>
                        </td>
                        <td class="p-5">
                            <div class="space-y-1">
                                @foreach($order->orderItems as $item)
                                    <div class="text-[11px]">
                                        ● <span class="font-bold text-gray-900">{{ $item->product->nama_menu ?? 'Menu Terhapus' }}</span> 
                                        <span class="text-gray-400 font-semibold">(x{{ $item->qty }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="p-5 font-bold text-gray-900">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td class="p-5 text-center">
                            @if($order->status_pembayaran === 'Dibayar')
                                <span class="bg-emerald-50 text-emerald-600 font-bold text-[10px] py-1 px-2.5 rounded-full border border-emerald-100">Dibayar</span>
                            @elseif($order->status_pembayaran === 'Pending')
                                <span class="bg-amber-50 text-amber-600 font-bold text-[10px] py-1 px-2.5 rounded-full border border-amber-100">Pending</span>
                            @elseif($order->status_pembayaran === 'Kadaluwarsa')
                                <span class="bg-gray-100 text-gray-500 font-bold text-[10px] py-1 px-2.5 rounded-full border border-gray-200">Kadaluwarsa</span>
                            @else
                                <span class="bg-red-50 text-red-600 font-bold text-[10px] py-1 px-2.5 rounded-full border border-red-100">Gagal</span>
                            @endif
                        </td>
                        <td class="p-5 text-center">
                            @if($order->status_pesanan === 'Selesai')
                                <span class="bg-emerald-500 text-white font-bold text-[10px] py-1 px-2.5 rounded-full">Selesai</span>
                            @elseif($order->status_pesanan === 'Dikirim')
                                <span class="bg-blue-500 text-white font-bold text-[10px] py-1 px-2.5 rounded-full">Dikirim</span>
                            @elseif($order->status_pesanan === 'Diproses')
                                <span class="bg-indigo-500 text-white font-bold text-[10px] py-1 px-2.5 rounded-full">Diproses</span>
                            @elseif($order->status_pesanan === 'Pending')
                                <span class="bg-amber-500 text-white font-bold text-[10px] py-1 px-2.5 rounded-full">Pending</span>
                            @else
                                <span class="bg-red-500 text-white font-bold text-[10px] py-1 px-2.5 rounded-full">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="p-5 text-xs">
                            <span class="font-bold text-gray-900 block">📅 {{ date('d M Y', strtotime($order->tanggal_pengiriman)) }}</span>
                            <span class="text-[10px] text-gray-400 line-clamp-1 max-w-xs">{{ $order->alamat }}</span>
                        </td>
                        <td class="p-5 text-right flex justify-end gap-2 items-center min-h-[70px]">
                            <a href="{{ route('orders.edit', $order->id) }}" class="p-2 rounded-xl bg-gray-50 hover:bg-olive-50 text-gray-500 hover:text-olive-600 transition-all font-bold">Edit</a>
                            
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-xl bg-gray-50 hover:bg-red-50 text-gray-500 hover:text-red-600 transition-all font-bold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-10 text-center text-gray-400">Belum ada pesanan masuk.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="p-5 border-t border-gray-100 bg-gray-50">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection