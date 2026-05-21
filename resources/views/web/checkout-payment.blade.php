@extends('layouts.app')

@section('content')
<section class="py-12 bg-gray-50">
    <div class="max-w-xl mx-auto px-4">
        
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
                <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 font-semibold flex items-center justify-center text-xs">4</span>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8 text-center">
            <span class="text-4xl mb-4 block">💳</span>
            <h1 class="font-title font-extrabold text-2xl text-gray-900 mb-2">Selesaikan Pembayaran</h1>
            <p class="text-xs text-gray-400 font-semibold mb-6">Order ID: DINDA-{{ $order->id }}</p>
            
            <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-8">
                <span class="text-xs font-semibold text-gray-500 block mb-1">Total Pembayaran</span>
                <span class="font-title font-bold text-3xl text-olive-500">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>

            <button id="pay-button" class="w-full bg-accent-500 hover:bg-accent-600 text-white font-semibold py-4 rounded-full text-center text-sm shadow-md shadow-accent-500/10 hover:shadow-lg transition-all mb-4">
                Bayar Sekarang
            </button>
            
            <a href="{{ route('home') }}" class="text-xs text-gray-400 hover:text-gray-500 font-semibold underline">Bayar nanti (Ke Halaman Utama)</a>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<!-- Midtrans Snap JS (Sandbox) -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-W2Q1e4k1N1p0n1O9') }}"></script>
<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    const snapToken = "{{ $snapToken }}";

    if (payButton && snapToken) {
        payButton.addEventListener('click', function () {
            // Trigger snap popup
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id + "&status_code=" + result.status_code + "&transaction_status=" + result.transaction_status + "&transaction_id=" + result.transaction_id;
                },
                onPending: function(result){
                    window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id + "&status_code=" + result.status_code + "&transaction_status=" + result.transaction_status + "&transaction_id=" + result.transaction_id;
                },
                onError: function(result){
                    window.location.href = "{{ route('payment.finish') }}?order_id=" + result.order_id + "&status_code=" + result.status_code + "&transaction_status=" + result.transaction_status + "&transaction_id=" + result.transaction_id;
                },
                onClose: function(){
                    alert('Anda menutup popup pembayaran sebelum menyelesaikan transaksi.');
                }
            });
        });
    }
</script>
@endsection
