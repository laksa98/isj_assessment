<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransactionRequest;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    /**
     * The TransactionService instance.
     *
     * @var \App\Services\TransactionService
     */
    private TransactionService $transactionService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\TransactionService  $transactionService
     * @return void
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Handle a bet request.
     *
     * This method is responsible for processing a betting request. It utilizes the TransactionService 
     * to handle the business logic for placing a bet based on the validated data provided in the 
     * TransactionRequest.
     *
     * @param  \App\Http\Requests\Api\TransactionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bet (TransactionRequest $request)
    {
        // return response()->json($request);
        $response = $this->transactionService->processBet($request);

        return response()->json($response);
    }


    /**
     * Handle a result request.
     *
     * This method is responsible for processing a result request. It uses the TransactionService to 
     * handle the business logic for processing results based on the validated data provided in the 
     * TransactionRequest.
     *
     * @param  \App\Http\Requests\Api\TransactionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function result (TransactionRequest $request)
    {
        $response = $this->transactionService->processResult($request);

        return response()->json($response);
    }
}
