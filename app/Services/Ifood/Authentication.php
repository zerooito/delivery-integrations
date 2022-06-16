<?php

namespace App\Services\Ifood;
use Illuminate\Support\Facades\Http;
use Log;

class Authentication
{
    private $clientId;
    private $clientSecret;
    private $urlIfood;


    public function __construct($clientId, $clientSecret)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->urlIfood = env('URL_IFOOD') . '/authentication/v1.0/oauth/token';
    }

    public function generateToken()
    {
        $response = Http::asForm()->post($this->urlIfood, [
            'grantType' => 'client_credentials',
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret
        ]);
        
        Log::debug('Response generate token: ' . var_export($response, true));

        if (!$response->ok()) {
            return [];
        }

        return json_decode($response->body(), true);
    }
}
