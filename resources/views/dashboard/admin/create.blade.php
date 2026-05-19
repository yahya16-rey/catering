<x-layouts.app :title="__('Tambah Pesanan')">
    <div class="max-w-2xl mx-auto py-10">
        <flux:heading size="xl" class="mb-6">Tambah Pesanan</flux:heading>

        <form action="{{ route('orders.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <flux:label for="name">Nama Pembeli</flux:label>
                <flux:input type="text" name="name" required />
            </div>

            <div>
                <flux:label for="phone">Nomor WhatsApp</flux:label>
                <flux:input type="text" name="phone" required pattern="08\d{10}" title="Nomor harus 12 digit. Contoh: 081234567890" />
            </div>

            <div>
                <flux:label for="product_id">Produk</flux:label>
                <select name="product_id" required class="form-select w-full px-4 py-2 rounded border text-gray-900">
                    <option value="">-- Pilih Produk --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <flux:label for="quantity">Jumlah</flux:label>
                <flux:input type="number" name="quantity" min="1" required />
            </div>

            <div>
                <flux:label for="note">Catatan</flux:label>
                <flux:textarea name="note" />
            </div>

            <div>
                <flux:label for="status">Status</flux:label>
                <select name="status" class="form-select w-full px-4 py-2 rounded border">
                    <option value="pending">Pending</option>
                    <option value="diproses">Diproses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>

            <flux:button type="submit" variant="primary">
                Simpan Pesanan
            </flux:button>
        </form>
    </div>
</x-layouts.app>
