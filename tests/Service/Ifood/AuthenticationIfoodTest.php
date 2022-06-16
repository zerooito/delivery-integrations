<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Ifood\Authentication;

class AutheticationIfoodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A test to verify if token is generated
     *
     * @return void
     */
    public function test_if_access_token_generate()
    {
        \App\Models\Customer::factory(1)->create();

        $data = \App\Models\Customer::first();
        
        $autheticationIfood = new Authentication($data['client_id'], $data['client_secret']);
        $token = $autheticationIfood->generateToken();

        $this->assertNotEmpty($token);
    }

    /**
     * A test to verify if token is empty when not correct client_id and client_secret
     *
     * @return void
     */
    public function test_if_access_token_generate_empty()
    {   
        $autheticationIfood = new Authentication('wrong_client_id', 'wrong_client_secret');
        $token = $autheticationIfood->generateToken();

        $this->assertEmpty($token);
    }
}
