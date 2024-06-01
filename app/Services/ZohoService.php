<?php


namespace App\Services;


use App\Classes\Api\ZohoAuth;
use App\Classes\Api\ZohoApi;

class ZohoService
{
    protected ZohoAuth $auth;
    private string $token;
    private string $requestUrl;
    private ZohoApi $zoho;

    public function __construct(ZohoAuth $auth)
    {
        $this->token = $auth->getAccessToken();
        $this->requestUrl = $auth->getRequestUrl();
        $this->zoho = new ZohoApi($this->token, $this->requestUrl);
    }

    public function getLeads()
    {
        return $this->zoho->getLeads();
    }

    public function createAccount(array $accountData)
    {
        return $this->zoho->createAccount($accountData);
    }

    public function createDeal(array $dealData)
    {
        return $this->zoho->createDeal($dealData);
    }

    public function createAccountAndDeal(array $accountData, array $dealData)
    {
        return $this->zoho->createAccountAndDeal($accountData, $dealData);
    }
}
