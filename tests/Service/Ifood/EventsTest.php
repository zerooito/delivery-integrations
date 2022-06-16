<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Ifood\Authentication;
use App\Services\Ifood\Events;
use App\Services\Ifood\Merchant;

class EventsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to post acknowledge events
     *
     * @return void
     */
    public function test_post_acknowledge_events()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();

        $events = new Events($token['accessToken'], $merchants[0]['id']);
        $params = [
            [
                "createdAt" => "2019-09-19T13:40:11.822Z",
                "fullCode" => "PLACED",
                "metadata" => [
                  "additionalProp1" => [],
                  "additionalProp2" => [],
                  "additionalProp3" => []
                ],
                "code" => "PLC",
                "orderId" => "07110e1b-8191-4670-baed-407219481ffb",
                "id" => "cd40582b-0ef2-4d52-bc7c-507fdff12e21"
            ]
        ];
        $event = $events->postAcknowledgment($params);

        $this->assertEmpty($event);
    }

    /**
     * A test to get list from events polling is empty
     *
     * @return void
     */
    public function test_get_polling_events()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();

        $events = new Events($token['accessToken'], $merchants[0]['id']);
        $event = $events->getEventsPolling();

        $this->assertEmpty($event);
    }
    
}
