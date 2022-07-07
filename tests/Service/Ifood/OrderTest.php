<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;
use Mockery\MockInterface;
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
    // public function test_confirm_order()
    // {
    //     \App\Models\Customer::factory(1)->create();

    //     $data = \App\Models\Customer::first();
        
    //     $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
    //     $token = $autheticationIfood->generateToken();

    //     $merchant = new Merchant($token['accessToken']);
    //     $merchants = $merchant->getMerchants();

    //     $events = new Events($token['accessToken'], $merchants[0]['id']);
    //     $event = $events->getEventsPolling();

    //     if (!empty($event)) {
    //         $params = [
    //             [
    //                 "createdAt" => $event[0]['createdAt'],
    //                 "fullCode" => $event[0]['fullCode'],
    //                 "code" => $event[0]['code'],
    //                 "orderId" => $event[0]['orderId'],
    //                 "id" => $event[0]['id'],
    //                 "customer_id" => $data['id']
    //             ]
    //         ];

    //         $events->postAcknowledgment($params);
    //     }

    //     $ordersPending = \App\Models\Order::getAllOrdersPlaced($data['id']);

    //     if (!empty($ordersPending)) {
    //         $orders = new Orders($token['accessToken']);
    //         $orderConfirm = $orders->confirmByOrderId($ordersPending[0]->order_id);

    //         $ordersPending = \App\Models\Order::getAllOrdersPlaced($data['id']);
            
    //         $this->assertEmpty($ordersPending);
    //         $this->assertEmpty($orderConfirm);
    //     } else {
    //         $this->assertEmpty($ordersPending);
    //     }
    // }

    public function test_confirm_start_preparation()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();
        
        $events = $this->instance(
            Events::class,
            Mockery::mock(Events::class, function (MockInterface $mock) {
                $mock->shouldReceive('getEventsPolling')->andReturn($this->eventsMockResponse());
            })
        );
        $event = $events->getEventsPolling();

        \App\Models\Order::factory(1)->create([
            'code' => 'CFM',
            'event_id' => $event[0]['id'],
            'full_code' => 'CONFIRMED',
            'status' => 'CONFIRMED',
            'customer_id' => $data['id']
        ]);

        $ordersConfirmed = \App\Models\Order::getAllOrdersConfirmed($data['id']);
        
        $orders = $this->instance(
            Orders::class,
            Mockery::mock(Orders::class, function (MockInterface $mock) {
                $mock->shouldReceive('startPrepareByOrderId')->andReturn([]);
            })
        );

        $orderPreparedOK = $orders->startPrepareByOrderId($ordersConfirmed[0]->order_id);

        $this->assertEmpty($orderPreparedOK);
    }

    private function eventsMockResponse()
    {
        return [
            [
                "id" => "order-test-id",
                "code" => "PLA",
                "fullCode" => "PLACED",
                "orderId" => "6c0c75f3-ad29-4f9c-864e-4cee95885985",
                "merchantId" => "c6cc036b-d013-4f56-ac1e-394afc5d1b7f",
                "createdAt" => "2022-07-07T21:47:04.656Z",
                "metadata" => [
                    "CANCEL_STAGE" => "[PRE_CONFIRMED]",
                    "ORIGIN" => "IfoodGatewayAgent",
                    "CANCEL_CODE" => "901",
                    "CANCELLATION_DISPUTE" => [
                        "IS_CONTESTABLE" => "CANCELLATION_IS_NOT_CONTESTABLE",
                        "REASON" => "NOT_REFUNDABLE_CANCELLATION",
                    ],
                    "CANCELLATION_OCCURRENCE" => [
                        "RESTAURANT" => [
                            "FINANCIAL_OCCURRENCE" => "NA",
                            "PAYMENT_TYPE" => "NA",
                        ],
                        "CONSUMER" => [
                            "FINANCIAL_OCCURRENCE" => "NA",
                            "PAYMENT_TYPE" => "NA",
                        ],
                        "LOGISTIC" => [
                            "FINANCIAL_OCCURRENCE" => "NA",
                            "PAYMENT_TYPE" => "NA",
                        ]
                    ],
                    "TIMEOUT_EVENT" => false,
                    "CANCEL_ORIGIN" => "SCHEDULER",
                    "CANCEL_REASON" => "AUTOMATICO - NAO ENVIADO PARA RESTAURANTE",
                    "CANCEL_USER" => "Order BackOffice Scheduler",
                    "CANCELLATION_REQUESTED_EVENT_ID" => "2638014e-095b-44ba-a9ce-15f2a01d3ecf"
                ]
            ]
        ];
    }

}
