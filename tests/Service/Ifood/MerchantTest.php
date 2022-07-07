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

    /**
     * A test to get status interruption
     * 
     * @return void
     */
    public function test_get_merchant_when_not_exist_interruption()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();
        $merchantsInterruption = $merchant->getMerchantInterruptionByMerchantId($merchants[0]['id']);

        if (!empty($merchantsInterruption)) {
            $response = $merchant->deleteMerchantInterruptionByIdAndMerchantId($merchants[0]['id'], $merchantsInterruption[0]['id']);
            $this->assertEmpty($response);
        } else {
            $this->assertEmpty($merchantsInterruption);
        }
    }

    /**
     * A test to get status interruption
     * 
     * @return void
     */
    public function test_create_interruption()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $merchant = new Merchant($token['accessToken']);
        $merchants = $merchant->getMerchants();

        $body = [
            'id' => $merchants[0]['id'],
            'description' => 'Parada de Teste',
            'start' => date('Y-m-d') . "T16:31:11.879Z",
            'end' => date('Y-m-d') . "T16:32:11.879Z"
        ];

        // $merchantsInterruption = $merchant->postMerchantInterruptionById($merchants[0]['id'], $body);

        // $this->assertNotEmpty($merchantsInterruption);
    }

}
