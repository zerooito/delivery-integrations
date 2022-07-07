<?php

namespace App\Services\Ifood;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Log;

class Orders
{
    const ACCEPTED = 202;

    private $accessToken;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->urlIfood = env('URL_IFOOD') . '/order/v1.0/orders/';
    }

    public function confirmByOrderId($orderId)
    {
        $response = Http::withToken($this->accessToken)->post($this->urlIfood. $orderId . '/confirm');

        $order = Order::where('order_id', $orderId)->get()->first();
        $order->status = 'CONFIRMED';
        $order->save();

        Log::debug('Response confirm order: ' . var_export($response, true));
        return json_decode($response->body(), true);
    }

    public function startPrepareByOrderId($orderId)
    {
        $response = Http::withToken($this->accessToken)->post($this->urlIfood. $orderId . '/startPreparation');

        $order = Order::where('order_id', $orderId)->get()->first();
        $order->status = 'PREPARATION_STARTED';
        $order->save();

        Log::debug('Response prepare order: ' . var_export($response, true));
        return json_decode($response->body(), true);
    }

}