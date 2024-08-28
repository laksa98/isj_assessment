<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Api\RefundRequest;
use App\Services\RefundService;


class RefundController extends Controller
{
    /**
     * The RefundService instance.
     *
     * @var \App\Services\RefundService
     */
    private RefundService $refundService;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Services\RefundService  $refundService
     * @return void
     */
    public function __construct(RefundService $refundService)
    {
        $this->refundService = $refundService;
    }

    /**
     * Handle a refund request.
     *
     * This method is responsible for processing the refund request. It leverages the RefundService to 
     * execute the refund logic based on the validated data provided in the RefundRequest.
     *
     * @param  \App\Http\Requests\Api\RefundRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refund (RefundRequest $request)
    {
        $response = $this->refundService->processRefund($request);

        return response()->json($response);
    }
}
