<?php

namespace App\Services;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Provider;
use App\Models\Game;
use App\Models\User;
use App\Helpers\Constants;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    /**
     * Process a bet transaction for a user.
     * 
     * @param  $request The bet request data, including userId, gameId, amount, etc.
     * @return array Response data containing transaction details or error information.
     */
    public function processBet($request)
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
            $game = Game::where('provider_game_id', $request->gameId)->first();
            // Check if the game is enabled
            if($game->status != Constants::GAME_STATUS_ENABLED)
            {
                return [
                    'error' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED_CODE,
                    'description' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED
                ];
            }
            DB::beginTransaction();
            // Check if a transaction with the same reference already exists
            $betTransaction = Transaction::where('reference', $request->reference)->first();
            if(!$betTransaction)
            {
                if ($user->wallet->balance < $request->amount)
                {
                    return [
                        'error' => Constants::RESPONSE_INSUFFICIENT_BALANCE_CODE,
                        'description' => Constants::RESPONSE_INSUFFICIENT_BALANCE
                    ];
                }
                
                $betTransaction = $this->saveTransaction($request, Constants::TRANSACTION_TYPE_BET);

                $user->wallet->balance -= $request->amount;
                $user->wallet->save();

                $betTransaction->status = Constants::TRANSACTION_STATUS_COMPLETED;
                $betTransaction->save();
            }

            DB::commit();

            // Return success response with transaction details
            return [
                'transactionId' => $betTransaction->id,
                'currency' => $user->wallet->currency,
                'cash' => $user->wallet->balance,
                'bonus' => $user->wallet->bonus,
                'usedPromo' => 0,
                'error' => Constants::RESPONSE_SUCCESS_CODE,
                'description' => Constants::RESPONSE_SUCCESS,
            ];

        }
        catch (\Exception $e)
        {
            // Rollback the transaction in case of an error
            DB::rollBack();
            return 
            [
                'error' => Constants::RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY_CODE,
                'description' => Constants::RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY,
                $e->getMessage(),
            ];
        }

    }

     /**
     * Process the result of a game transaction.
     * 
     * @param  $request The result request data, including userId, gameId, amount, etc.
     * @return array Response data containing transaction details or error information.
     */
    public function processResult($request)
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
            // Check if a transaction with the same reference already exists
            $resultTransaction = Transaction::where('reference', $request->reference)->first();
            $userWallet = Wallet::where('user_id', $request->userId)->first();

            if(!$resultTransaction)
            {
                $resultTransaction = $this->saveTransaction($request, Constants::TRANSACTION_TYPE_RESULT);

                $userWallet->balance += $request->amount;
                $userWallet->save();

                $resultTransaction->status = Constants::TRANSACTION_STATUS_COMPLETED;
                $resultTransaction->save();
            }

            DB::commit();

            // Find the game to ensure it's enabled
            $game = Game::where('provider_game_id', $request->gameId)->first();
            if($game->status != Constants::GAME_STATUS_ENABLED)
            {
                return [
                    'error' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED_CODE,
                    'description' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED
                ];
            }
            return [
                'transactionId' => $resultTransaction->id,
                'currency' => $userWallet->currency,
                'cash' => $userWallet->balance,
                'bonus' => $userWallet->bonus,
                'error' => Constants::RESPONSE_SUCCESS_CODE,
                'description' => Constants::RESPONSE_SUCCESS,
            ];
        }
        catch (\Exception $e)
        {
             // Rollback the transaction in case of an error
            DB::rollBack();
            return 
            [
                'error' => Constants::RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY_CODE,
                'description' => Constants::RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY,
            ];
        }
    }

    /**
     * Save a transaction to the database.
     * 
     * @param  $request The request data containing transaction details.
     * @param  $type The type of transaction (bet or result).
     * @return Transaction The newly created transaction.
     */
    public function saveTransaction($request, $type)
    {
        $provider = Provider::where('name', $request->providerId)->first();
        $game = Game::where('provider_game_id', $request->gameId)->first();

        $transaction = Transaction::create([
            'user_id' => $request->userId,
            'game_id' => $game->id,
            'round_id' => $request->roundId,
            'amount' => $request->amount,
            'reference' => $request->reference,
            'provider_id' => $provider->id,
            'timestamp' => $request->timestamp,
            'round_details' => $request->roundDetails,
            'type' => $type,
            'status' => Constants::TRANSACTION_STATUS_PROCESSING,
        ]);

        return $transaction;
    }
}

?>