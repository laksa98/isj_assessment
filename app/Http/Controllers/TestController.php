<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testGetGameUrl (Request $request)
    {
        
        $requiredFields = ['secureLogin', 'symbol', 'language', 'token', 'externalPlayerId'];

        // Check for missing fields
        foreach ($requiredFields as $field) {
            if (!$request->has($field)) {
                return response()->json([
                    'error' => 14,
                    'description' => "Required field is empty: {$field} is required",
                ], 400);
            }
        }

        // Example validation: Check for incorrect secure login and token
        if ($request->input('secureLogin') !== 'TestOperator' || $request->input('token') !== 'testToken') {
            return response()->json([
                'error' => 2,
                'description' => 'Incorrect secure LOGIN and secure password combination',
            ], 400);
        }

        // Example validation: Check for incorrect input parameters
        if ($request->input('externalPlayerId') != 1) {
            return response()->json([
                'error' => 7,
                'description' => 'One or several input parameters is not set or set incorrectly.',
            ], 400);
        }

        return response()->json([
            'error' => "0",
            'description' => "OK",
            'gameURL' => "https://test.com",
        ]);
    }

    public function testGetGame (Request $request)
    {
        if(!$request->has('secureLogin'))
        {
            return response()->json([
                'error' => 14,
                'description' => "Required field is empty: secureLogin is required",
            ], 400);
        }

        if ($request->input('secureLogin') !== 'TestOperator') {
            return response()->json([
                'error' => 2,
                'description' => 'Incorrect secure LOGIN and secure password combination',
            ], 400);
        }

        return response()->json([
            'error' => 0,
            'description' => 'OK',
            'gameList' => [
                [
                    "gameID" => "vs20olympgate",
                    "gameName" => "Gates of Olympus",
                    "gameTypeID" => "vs",
                    "typeDescription" => "Video Slots",
                    "technology" => "html5",
                    "platform" => "MOBILE WEB",
                    "demoGameAvailable" => true,
                    "aspectRatio" => "16:9",
                    "technologyID" => "H5",
                    "gameIdNumeric" => 1605284987,
                    "jurisdictions" => [
                        "RS",
                        "X1",
                        "ON"
                    ],
                    "frbAvailable" => true,
                    "variableFrbAvailable" => true,
                    "lines" => 20,
                    "dataType" => "RNG",
                    "features" => [
                        "ANTE",
                        "BUY"
                    ],
                ],
                [
                    "gameID" => "vs20doghouse",
                    "gameName" => "The Dog House",
                    "gameTypeID" => "vs",
                    "typeDescription" => "Video Slots",
                    "technology" => "html5",
                    "platform" => "MOBILE WEB",
                    "demoGameAvailable" => true,
                    "aspectRatio" => "16:9",
                    "technologyID" => "H5",
                    "gameIdNumeric" => 1547739735,
                    "jurisdictions" => [
                        "GR",
                        "DE",
                        "ON"
                    ],
                    "frbAvailable" => true,
                    "variableFrbAvailable" => true,
                    "lines" => 20,
                    "dataType" => "RNG",
                    "features" => [],
                ]
            ],
        ]);
    }

    public function testHealthCheck (Request $request)
    {
        return response()->json([
            'error' => 0,
            'decription' => 'OK',
        ], 200);
    }
}
