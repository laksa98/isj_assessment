<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Provider;
use Illuminate\Support\Facades\Http;
use App\Helpers\Constants;


class ProviderController extends Controller
{
    /**
     * Get request data and return as JSON.
     */
    public function index (Request $request)
    {
        return response()->json($request, 201);
    }

    /**
     * Store a new provider.
     */
    public function store (Request $request)
    {
        $provider = Provider::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return response()->json($provider, 201);
    }

     /**
     * Perform a health check on the provider.
     */
    public function healthCheck ()
    {
        $provider = Provider::find(1);
        $url = env('PRAGMATIC_PLAY_HEALTH_CHECK', 'http://localhost/ISJ-tect-assessment/public/api/testHealthCheck');
        $response = Http::get($url);

        if ($response->successful())
        {
            $provider->status = Constants::PROVIDER_STATUS_ACTIVE;
            $provider->save();
            return response()->json([
                'message' => 'Request was successful',
                'status' => $response->status(),
            ]);
        }
        else
        {
            $provider->status = Constants::PROVIDER_STATUS_INACTIVE;
            $provider->save();
            return response()->json([
                'message' => 'Request failed',
                'status' => $response->status(),
            ]);
        }
    }
}
