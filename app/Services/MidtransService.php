<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected $serverKey;
    protected $isProduction;
    protected $snapUrl;

    public function __construct()
    {
        $this->serverKey = env('MIDTRANS_SERVER_KEY');
        $this->isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $this->snapUrl = $this->isProduction 
            ? 'https://app.midtrans.com/snap/v1/transactions' 
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    /**
     * Get Snap Token from Midtrans
     */
    public function getSnapToken($order, $user, $items)
    {
        $itemDetails = [];
        foreach ($items as $item) {
            $itemDetails[] = [
                'id' => $item->product_id,
                'price' => (int) $item->product->harga,
                'quantity' => (int) $item->qty,
                'name' => substr($item->product->nama_menu, 0, 50),
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => 'WIDIA-' . $order->id . '-' . time(),
                'gross_amount' => (int) $order->total_harga,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
            ]
        ];

        try {
            $response = Http::timeout(5)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode($this->serverKey . ':'),
            ])->post($this->snapUrl, $params);

            if ($response->successful()) {
                return $response->json()['token'];
            }

            Log::error('Midtrans API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('Midtrans Exception: ' . $e->getMessage());
            return null;
        }
    }
}
