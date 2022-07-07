<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Ifood\Authentication;
use App\Services\Ifood\Events;
use App\Services\Ifood\Orders;
use App\Services\Ifood\Merchant;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to get list from merchants
     *
     * @return void
     */
    public function test_confirm_order()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();

        $events = new Events($token['accessToken'], $merchants[0]['id']);
        $event = $events->getEventsPolling();

        if (!empty($event)) {
            $params = [
                [
                    "createdAt" => $event[0]['createdAt'],
                    "fullCode" => $event[0]['fullCode'],
                    "code" => $event[0]['code'],
                    "orderId" => $event[0]['orderId'],
                    "id" => $event[0]['id'],
                    "customer_id" => $data['id']
                ]
            ];

            $events->postAcknowledgment($params);
        }

        $ordersPending = \App\Models\Order::where('customer_id', $data['id'])->first();

        $orders = new Orders($token['accessToken']);
        $orderConfirm = $orders->confirmByOrderId($ordersPending->order_id);

        $this->assertEmpty($orderConfirm);
    }

}
