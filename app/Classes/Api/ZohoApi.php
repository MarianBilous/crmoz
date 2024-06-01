<?php


namespace App\Classes\Api;


use GuzzleHttp\Client;

class ZohoApi
{
    private Client $client;

    public function __construct(private string $token, private string $requestUrl)
    {
        $this->client = new Client();
    }

    public function getLeads()
    {
        $headers = [
            'Authorization' => 'Zoho-oauthtoken ' . $this->token,
        ];

        $response = $this->client->request('GET', $this->requestUrl . '/crm/v3/settings/modules', [
            'headers' => $headers,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function createAccount(array $accountData)
    {
        $response = $this->client->post($this->requestUrl . '/crm/v3/Accounts', [
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken ' . $this->token,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'data' => [$accountData]
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function createDeal(array $dealData)
    {
        $response = $this->client->post($this->requestUrl . '/crm/v3/Deals', [
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken ' . $this->token,
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'data' => [$dealData]
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function createAccountAndDeal(array $accountData, array $dealData)
    {
        $accountResponse = $this->createAccount($accountData);

        if (isset($accountResponse['data'][0]['details']['id'])) {
            $accountId = $accountResponse['data'][0]['details']['id'];

            $dealData['Account_Name'] = ['id' => $accountId];

            return $this->createDeal($dealData);
        }

        return ['error' => 'Failed to create account'];
    }
}
