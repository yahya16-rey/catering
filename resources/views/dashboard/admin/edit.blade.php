<x-layouts.app :title="__('Edit Order')">
    <div class="relative mb-6 w-full">
        <flux:heading size="xl">Edit Pemesanan</flux:heading>
        <flux:subheading size="lg" class="mb-6">Perbarui status, jumlah, atau informasi pesanan</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    @if(session()->has('success'))
    <flux:badge color="lime" class="mb-3 w-full">{{ session('success') }}</flux:badge>
    @elseif(session()->has('error'))
    <flux:badge color="red" class="mb-3 w-full">{{ session('error') }}</flux:badge>
    @endif

    <form action="{{ route('orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <flux:input label="Nama Pembeli" name="name" value="{{ $order->name }}" class="mb-3" disabled />

        <flux:input label="Nomor WA" name="phone" value="{{ $order->phone }}" class="mb-3" disabled />

        <flux:input label="Produk" name="product_name" value="{{ $order->product->name ?? '-' }}" class="mb-3" disabled />

        <flux:textarea label="Catatan" name="note" class="mb-3" disabled>{{ $order->note }}</flux:textarea>

        <flux:input type="number" label="Jumlah Pesanan" name="quantity" value="{{ $order->quantity ?? 1 }}" min="1" class="mb-3" />

        <flux:select label="Status" name="status" class="mb-3">
            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="diproses" {{ $order->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
            <option value="selesai" {{ $order->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </flux:select>

        <flux:input label="Total Harga (otomatis dihitung)" name="total_price"
            value="Rp {{ number_format($order->total_price, 0, ',', '.') }}"
            class="mb-3" disabled />

        <flux:separator />

        <div class="mt-4">
            <flux:button type="submit" variant="primary">Simpan Perubahan</flux:button>
            <flux:link href="{{ route('orders.index') }}" variant="ghost" class="ml-3">Kembali</flux:link>
        </div>
    </form>
    @if(session('open_whatsapp'))
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const phone = '{{ session('open_whatsapp') }}';
            const nomor = phone.replace(/^0/, '62'); // ubah 08xxx jadi 62xxx
            const pesan = encodeURIComponent("Halo, pesanan Anda telah selesai. Silakan dikonfirmasi. Terima kasih!");
            const url = `https://wa.me/${nomor}?text=${pesan}`;

            // Buka WhatsApp di tab baru
            window.open(url, '_blank');
        });
    </script>
@endif

</x-layouts.app>