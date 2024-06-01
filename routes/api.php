<?php

use App\Http\Controllers\ZohoCRMController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('zoho-leads', [ZohoCRMController::class, 'getLeads'])->name('get_leads');
Route::post('zoho-create-account', [ZohoCRMController::class, 'createAccount']);
Route::post('zoho-create-deal', [ZohoCRMController::class, 'createDeal']);
Route::post('zoho-create-account-and-deal', [ZohoCRMController::class, 'createAccountAndDeal']);
