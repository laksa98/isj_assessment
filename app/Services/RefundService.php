<?php

namespace App\Services;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Provider;
use App\Models\Game;
use App\Helpers\Constants;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RefundService
{
     /**
     * Process the refund for a user based on the provided request data.
     * 
     * @param  $request The refund request data, including userId, reference, etc.
     * @return array Response data containing error codes and descriptions, or success data.
     */
    public function processRefund($request)
    {
        try {
            $user = User::with('wallet')->find($request->userId);
            if($user->status != Constants::ACCOUNT_STATUS_ACTIVE)
            {
                return [
                    'error' => Constants::RESPONSE_PLAYER_IS_FROZEN_CODE,
                    'description' => Constants::RESPONSE_PLAYER_IS_FROZEN
                ];
            }
            DB::beginTransaction();
            $transaction = Transaction::where('reference', $request->reference)->first();
             // If the transaction doesn't exist, return success 
            if(!$transaction)
            {
                return [
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                ];
            }

            $existingTransaction = Transaction::where('reference', $request->reference)->where('type', Constants::TRANSACTION_TYPE_REFUND)->first();

            // If the refund transaction already exists, return success
            if($existingTransaction)
            {
                return [
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                ];
            }

            $userWallet = Wallet::where('user_id', $request->userId)->first();

            $refundTransaction = $this->saveRefundTransaction($transaction, Constants::TRANSACTION_TYPE_REFUND);

            $userWallet->balance += $refundTransaction->amount;
            $userWallet->save();

            DB::commit();

            // Return a success response with the transaction ID
            return [
                'transactionId' => $refundTransaction->id,
                'error' => Constants::RESPONSE_SUCCESS_CODE,
                'description' => Constants::RESPONSE_SUCCESS,
            ];
        }
        catch (\Exception $e)
        {
            // Rollback the database transaction in case of an error
            DB::rollBack();
            return 
            [
                'error' => Constants::RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY_CODE,
                'description' => Constants::RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY,
            ];
        }
    }

    public function saveRefundTransaction($transaction, $type)
    {
        // Create a new refund transaction with the same details as the original transaction
        $refundTransaction = Transaction::create([
            'user_id' => $transaction->user_id,
            'game_id' => $transaction->game_id,
            'round_id' => $transaction->round_id,
            'amount' => $transaction->amount,
            'reference' => $transaction->reference,
            'provider_id' => $transaction->provider_id,
            'timestamp' => $transaction->timestamp,
            'round_details' => $transaction->round_details,
            'type' => $type,
            'status' => Constants::TRANSACTION_STATUS_PROCESSING,
        ]);

        return $refundTransaction;
    }
}

?>