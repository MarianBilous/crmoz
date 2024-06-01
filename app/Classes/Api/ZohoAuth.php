<?php


namespace App\Classes\Api;


use App\Models\ZohoToken;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;

class ZohoAuth
{
    protected Client $client;
    protected string $accessToken;
    protected string $grantToken;
    protected string $clientId;
    protected string $clientSecret;
    protected string $requestUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->grantToken = env('ZOHO_GRANT_TOKEN');
        $this->clientId = env('ZOHO_CLIENT_ID');
        $this->clientSecret = env('ZOHO_CLIENT_SECRET');
    }

    public function getAccessToken()
    {
        $token = ZohoToken::orderBy('id', 'desc')->first();

        if (!$this->checkAccessToken($token)) {
            if (optional($token)->refresh_token) {
                return $this->refreshAccessToken(optional($token)->refresh_token);
            } else {
                $response = $this->getToken();
                $this->checkedResponseTokens($response);

                return $response->access_token;
            }
        }

        $this->setRequestUrl($token->api_domain);

        return $token->access_token;
    }

    private function checkAccessToken($token): bool
    {
        if (!$token) {
            return false;
        }

        $createdAt = $token->created_at;

        if (is_string($createdAt)) {
            $createdAt = Carbon::parse($createdAt);
        }

        if (!$createdAt instanceof Carbon) {
            return false;
        }

        $time = $createdAt->timestamp;

        if ($time + $token->expires_in < time()) {
            return false;
        }

        return true;
    }

    public function refreshAccessToken(string $refreshToken)
    {
        $newAccessToken = $this->refresh($refreshToken);

        $this->checkedResponseTokens($newAccessToken, $refreshToken);

        return $newAccessToken->access_token;
    }

    private function updateDbTokens(object $accessToken, string $refreshToken = ''): bool
    {
        $data = [
            'access_token'  => $accessToken->access_token,
            'refresh_token' => $accessToken->refresh_token ?? $refreshToken,
            'expires_in'    => $accessToken->expires_in,
            'api_domain'    => $accessToken->api_domain,
            'created_at'    => Carbon::now()
        ];

        $result = ZohoToken::create($data);

        if ($result) {
            return true;
        }

        return false;
    }

    public function storeTokens($data)
    {
        ZohoToken::updateOrCreate([], [
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'api_domain' => $data['api_domain'],
            'expires_in' => $data['expires_in'],
            'created_at' => Carbon::now()
        ]);
    }

    private function refresh(string $refreshToken)
    {
        $response = $this->client->post('https://accounts.zoho.com/oauth/v2/token', [
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $refreshToken,
            ],
        ]);

        return json_decode($response->getBody()->getContents());
    }

    public function getToken()
    {
        $response = $this->client->post('https://accounts.zoho.com/oauth/v2/token', [
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code'          => $this->grantToken,
            ],
        ]);

        return json_decode($response->getBody()->getContents());
    }

    private function checkedResponseTokens($newAccessToken, string $refreshToken = '')
    {
        if (!isset($newAccessToken->access_token)) {
            throw new Exception("Access token error");
        }

        $dbUpdate = $this->updateDbTokens($newAccessToken, $refreshToken);

        if (!$dbUpdate) {
            throw new Exception("Insert DB Error");
        }

        $this->setRequestUrl($newAccessToken->api_domain);
    }

    private function setRequestUrl($api_domain): void
    {
        $this->requestUrl = $api_domain;
    }

    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }
}
