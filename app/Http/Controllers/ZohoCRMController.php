<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountDealFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\ZohoService;

class ZohoCRMController extends Controller
{
    protected $zohoService;

    public function __construct(ZohoService $zohoService)
    {
        $this->zohoService = $zohoService;
    }

    public function createAccount(Request $request)
    {
        $accountData = $request->all();
        $result = $this->zohoService->createAccount($accountData);

        return response()->json($result);
    }

    public function createDeal(Request $request)
    {
        $dealData = $request->all();
        $result = $this->zohoService->createDeal($dealData);

        return response()->json($result);
    }

    public function createAccountAndDeal(AccountDealFormRequest $request)
    {
        $accountData = [
            'Account_Name' => $request->input('account_name'),
            'Website' => $request->input('account_website'),
            'Phone' => $request->input('account_phone')
        ];

        $dealData = [
            'Deal_Name' => $request->input('deal_name'),
            'Stage' => $request->input('deal_stage'),
        ];

        $result = $this->zohoService->createAccountAndDeal($accountData, $dealData);

        if (isset($result['error'])) {
            return response()->json(['message' => 'Failed to create records', 'details' => $result['error']], 500);
        }

        return response()->json(['message' => 'Records successfully created']);
    }
}
