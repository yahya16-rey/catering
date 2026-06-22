<table>
    <tr>
        <td colspan="9" style="text-align: center; font-size: 20px; font-weight: bold; color: #1a365d;">DINDA CATERING</td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-size: 14px; font-weight: bold; color: #000000;">LAPORAN PENJUALAN CATERING MAKANAN</td>
    </tr>
    <tr>
        <td colspan="9" style="text-align: center; font-size: 12px; font-style: italic; border-bottom: 2px solid #000;">Periode: {{ $period }}</td>
    </tr>
    <tr>
        <td colspan="9"></td>
    </tr>
    <tr>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">No</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Tanggal</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">ID Pesanan</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Paket/Menu</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Jumlah Porsi</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Harga/Porsi</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Total (Rp)</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Metode Pembayaran</th>
        <th style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">Status</th>
    </tr>
    @php
        $no = 1;
        $totalPorsi = 0;
        $totalPenjualan = 0;
        $uniqueOrders = [];
    @endphp
    @foreach($orders as $order)
        @php
            $uniqueOrders[$order->id] = true;
        @endphp
        @foreach($order->orderItems as $item)
            @php
                $itemTotal = $item->qty * $item->harga;
                $totalPorsi += $item->qty;
                $totalPenjualan += $itemTotal;
            @endphp
            <tr>
                <td style="text-align: center; border: 1px solid #000000;">{{ $no++ }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $order->created_at->format('d/m/Y') }}</td>
                <td style="text-align: center; border: 1px solid #000000;">PSN{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td style="border: 1px solid #000000;">{{ $item->product->nama_menu ?? 'Menu Terhapus' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $item->qty }}</td>
                <td style="text-align: right; border: 1px solid #000000;">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                <td style="text-align: right; border: 1px solid #000000;">Rp{{ number_format($itemTotal, 0, ',', '.') }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ $order->payment ? ucfirst($order->payment->metode ?? 'Transfer') : 'Transfer' }}</td>
                <td style="text-align: center; border: 1px solid #000000;">{{ ucfirst($order->status_pesanan) }}</td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        <td colspan="9"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="4" style="background-color: #0f172a; color: #ffffff; font-weight: bold; text-align: center; border: 1px solid #000000;">RINGKASAN PENJUALAN</td>
        <td></td>
        <td></td>
        <td colspan="2" style="text-align: center;">Tegal, {{ now()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="background-color: #e2e8f0; border: 1px solid #000000;">Total Pesanan Penjualan</td>
        <td colspan="2" style="text-align: center; border: 1px solid #000000;">{{ count($uniqueOrders) }} Pesanan</td>
        <td></td>
        <td></td>
        <td colspan="2" style="text-align: center; font-weight: bold;">Pemilik Dinda Catering</td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="background-color: #e2e8f0; border: 1px solid #000000;">Total Porsi Terjual</td>
        <td colspan="2" style="text-align: center; border: 1px solid #000000;">{{ $totalPorsi }} Porsi</td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td></td>
        <td colspan="2" style="background-color: #fde047; font-weight: bold; border: 1px solid #000000;">TOTAL PENJUALAN</td>
        <td colspan="2" style="background-color: #fde047; font-weight: bold; text-align: center; border: 1px solid #000000;">Rp{{ number_format($totalPenjualan, 0, ',', '.') }}</td>
        <td></td>
        <td></td>
        <td colspan="2"></td>
    </tr>
    <tr>
        <td colspan="9"></td>
    </tr>
    <tr>
        <td colspan="4" style="font-weight: bold;">Dicetak pada: {{ now()->translatedFormat('d F Y') }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td colspan="2" style="text-align: center; font-weight: bold;">(........................................)</td>
    </tr>
</table>
