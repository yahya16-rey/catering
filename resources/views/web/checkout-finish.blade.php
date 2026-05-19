@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-xl mx-auto px-4 text-center">
        
        <!-- Step Indicators -->
        <div class="flex justify-between items-center max-w-md mx-auto mb-16 relative">
            <div class="absolute left-0 right-0 h-0.5 bg-gray-200 top-1/2 -translate-y-1/2 z-0"></div>
            
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-olive-500 text-white font-semibold flex items-center justify-center text-xs">1</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-olive-500 text-white font-semibold flex items-center justify-center text-xs">2</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-olive-500 text-white font-semibold flex items-center justify-center text-xs">3</span>
            </div>
            <div class="relative z-10 flex flex-col items-center">
                <span class="w-8 h-8 rounded-full bg-olive-500 text-white font-semibold flex items-center justify-center text-xs">4</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
            @if($status === 'settlement' || $status === 'capture')
                <span class="text-5xl mb-4 block">✅</span>
                <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-2">Pembayaran Berhasil!</h1>
                <p class="text-sm text-gray-500 leading-relaxed mb-8">Terima kasih atas pesanan Anda. Kami sedang mempersiapkan hidangan spesial Anda. Tim kami akan segera menghubungi Anda untuk koordinasi pengiriman.</p>
            @elseif($status === 'pending')
                <span class="text-5xl mb-4 block">⏳</span>
                <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-2">Pembayaran Tertunda</h1>
                <p class="text-sm text-gray-500 leading-relaxed mb-8">Transaksi Anda sedang diproses atau menunggu pembayaran dari metode yang Anda pilih. Silakan selesaikan pembayaran Anda.</p>
            @else
                <span class="text-5xl mb-4 block">❌</span>
                <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-2">Pembayaran Gagal</h1>
                <p class="text-sm text-gray-500 leading-relaxed mb-8">Maaf, transaksi Anda tidak dapat diselesaikan atau telah kedaluwarsa. Silakan coba memesan kembali.</p>
            @endif

            <div class="flex gap-4">
                <a href="{{ route('home') }}" class="flex-grow bg-olive-500 hover:bg-olive-600 text-white font-semibold py-3.5 rounded-full text-center text-sm shadow-md transition-all">Kembali ke Beranda</a>
                <a href="{{ route('menu.list') }}" class="flex-grow border border-gray-200 hover:border-olive-500 text-gray-700 hover:text-white hover:bg-olive-500 font-semibold py-3.5 rounded-full text-center text-sm transition-all">Pesan Menu Lain</a>
            </div>
        </div>
    </div>
</section>
@endsection
