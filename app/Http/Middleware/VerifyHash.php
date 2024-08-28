<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\Constants;
use App\Helpers\Misc;


class VerifyHash
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    /**
     * Handle an incoming request.
     *
     * This middleware checks if the hash passed in the request matches the
     * calculated hash from the request parameters (excluding the 'hash' field).
     * If the hash is invalid, it returns an error response with a specific
     * error code and description.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
     public function handle(Request $request, Closure $next)
    {
        $params = $request->except('hash');
        $receivedhash = $request->input('hash');

        $calculateHash = Misc::CalculateHash($params);

        if($calculateHash != $receivedhash){
            return response()->json([
                "error" => Constants::RESPONSE_INVALID_HASH_CODE_CODE,
                "desciption" => Constants::RESPONSE_INVALID_HASH_CODE,
            ]);
        }

        return $next($request);
    }
}
