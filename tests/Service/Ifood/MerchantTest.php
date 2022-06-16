<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Ifood\Authentication;
use App\Services\Ifood\Merchant;

class MerchantTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to get list from merchants
     *
     * @return void
     */
    public function test_get_merchants_list()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchants = new Merchant($token['accessToken']);
        $merchant = $merchants->getMerchants();

        $this->assertNotEmpty($merchant);
    }

    /**
     * A test to get info merchant satus
     *
     * @return void
     */
    public function test_get_info_merchant_status()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();
        $merchantStatus = $merchant->getMerchantStatus($merchants[0]['id']);

        $this->assertNotEmpty($merchantStatus);
    }

}
