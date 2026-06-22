<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class OrdersExport implements FromView, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View
    {
        $orders = Order::with(['user', 'orderItems.product', 'payment'])
            ->whereMonth('created_at', $this->month)
            ->whereYear('created_at', $this->year)
            ->get();
            
        Carbon::setLocale('id');
        $dateObj = Carbon::createFromDate($this->year, $this->month, 1);
        $period = $dateObj->translatedFormat('F Y');

        return view('exports.orders', [
            'orders' => $orders,
            'period' => $period
        ]);
    }
}
