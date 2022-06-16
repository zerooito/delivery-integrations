<?php

namespace App\Services\Ifood;
use Illuminate\Support\Facades\Http;
use Log;

class Merchant
{
    private $accessToken;

    public function __construct($accessToken)
    {
        $this->accessToken = $accessToken;
        $this->urlIfood = env('URL_IFOOD') . '/merchant/v1.0/merchants';
    }

    public function getMerchants() {
        $response = Http::withToken($this->accessToken)->get($this->urlIfood);
        return json_decode($response->body(), true);
    }

    public function getMerchantStatus($merchantId)
    {
        $response = Http::withToken($this->accessToken)->get($this->urlIfood . '/' . $merchantId . '/status');
        return json_decode($response->body(), true);
    }

}