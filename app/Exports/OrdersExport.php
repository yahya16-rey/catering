<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection(): Collection
    {
        return Order::with(['user', 'orderItems.product'])
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get()
            ->map(function ($order) {
                $menus = $order->orderItems->map(function ($item) {
                    return ($item->product->nama_menu ?? 'Menu Terhapus') . ' (x' . $item->qty . ')';
                })->implode(', ');

                return [
                    'ID Pesanan'        => $order->id,
                    'Nama Customer'     => $order->user->name ?? 'Guest',
                    'Email Customer'    => $order->user->email ?? '-',
                    'Menu Dipesan'      => $menus,
                    'Total Harga'       => $order->total_harga,
                    'Status Pesanan'    => $order->status_pesanan,
                    'Status Pembayaran' => $order->status_pembayaran,
                    'Tanggal Kirim'     => $order->tanggal_pengiriman,
                    'Alamat Kirim'      => $order->alamat,
                    'Catatan'           => $order->catatan,
                    'Tanggal Order'     => $order->created_at->format('d-m-Y H:i')
                ];
            });
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Nama Customer',
            'Email Customer',
            'Menu Dipesan',
            'Total Harga',
            'Status Pesanan',
            'Status Pembayaran',
            'Tanggal Kirim',
            'Alamat Kirim',
            'Catatan',
            'Tanggal Order'
        ];
    }
}
