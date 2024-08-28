<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use App\Helpers\Constants;
use App\Http\Requests\Api\WalletRequest;

class WalletController extends Controller
{
    /**
     * Retrieve the balance of a user's wallet.
     *
     * This method fetches the wallet details for a specified user based on the user ID provided
     * in the WalletRequest. It then returns the user's currency, cash balance, bonus balance,
     * and a success response.
     *
     * @param  \App\Http\Requests\Api\WalletRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBalance (WalletRequest $request)
    {
        $userWallet = Wallet::where('user_id', $request->userId)->first();
        return response()->json([
            "currency" => $userWallet->currency,
            "cash" => $userWallet->balance,
            "bonus" => $userWallet->bonus,
            "error" => Constants::RESPONSE_SUCCESS_CODE,
            "description" => Constants::RESPONSE_SUCCESS,
        ]);
    }
}
