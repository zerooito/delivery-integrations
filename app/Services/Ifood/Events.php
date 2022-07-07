<?php

namespace App\Services\Ifood;
use Illuminate\Support\Facades\Http;
use App\Models\Order;
use Log;

class Events
{
    const NO_CONTENT = 204;

    private $accessToken;
    private $merchantId;

    public function __construct($accessToken, $merchantId)
    {
        $this->accessToken = $accessToken;
        $this->merchantId = $merchantId;
        $this->urlIfood = env('URL_IFOOD') . '/order/v1.0/events';
    }

    public function getEventsPolling()
    {
        $headers = ['x-polling-merchants' => $this->merchantId];
        $params = [
            'types' => 'PLC,REC,CFM',
            'groups' => 'ORDER_STATUS,CANCELLATION_REQUEST,ORDER_TAKEOUT,DELIVERY,DELIVERY_ONDEMAND,DELIVERY_COMPLEMENT,ORDER_MODIFIER'
        ];

        $response = Http::withHeaders($headers)->withToken(
                                                    $this->accessToken
                                                )
                                                ->get($this->urlIfood . ':polling', $params);
    
        if ($response->status() === self::NO_CONTENT) {
            Log::debug('No has events disponibles: ' . var_export($response, true));
        }

        return json_decode($response->body(), true);
    }

    public function postAcknowledgment($params)
    {
        $response = Http::withToken($this->accessToken)->post($this->urlIfood . '/acknowledgment', $params);
        var_dump($response->body());
        $order = new Order;
        
        $order->order_id =  $params[0]['orderId'];
        $order->event_id =  $params[0]['id'];
        $order->code =  $params[0]['code'];
        $order->full_code =  $params[0]['fullCode'];
        $order->customer_id =  $params[0]['customer_id'];

        $order->save();

        Log::debug('Response acknowledgement: ' . var_export($response, true));
        return json_decode($response->body(), true);
    }

}