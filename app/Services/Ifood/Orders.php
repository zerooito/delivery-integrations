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

        // TODO -> Fazer a mudança de status do pedido no banco de dados.

        Log::debug('Response acknowledgement: ' . var_export($response, true));
        return json_decode($response->body(), true);
    }

}