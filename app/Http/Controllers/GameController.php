<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Misc;
use Illuminate\Support\Facades\Http;
use App\Models\Game;
use App\Helpers\Constants;


class GameController extends Controller
{
    public function generateGameUrl (Request $request)
    {
        // Collect the request data for sending to the external game API
        $data = [
            'secureLogin' => $request->secureLogin,
            'symbol' => $request->symbol,
            'language' => $request->language,
            'token' => $request->token,
            'externalPlayerId' => $request->externalPlayerId
        ];

        // Generate hash for secure communication using custom helper
        $calculateHash = Misc::CalculateHash($data);

        $data['hash'] = $calculateHash;

        // Set the API URL, fallback to default in case the env variable is missing
        $url = env('PRAGMATIC_PLAY_GAME_GENERATE_URL',  "http://localhost/ISJ-tect-assessment/public/api/testGetGameUrl");

        $response = Http::asForm()->post($url, $data);

        // Check if the request was successful and return the corresponding response
        if ($response->successful()) 
        {
            return response()->json([
                'message' => 'Request was successful',
                'status' => $response->status(),
                'data' => json_decode($response->body()),
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Request failed',
                'status' => $response->status(),
                'data' => json_decode($response->body()),
            ]);
        }
    }

    public function getGames (Request $request)
    {
         // Collect the request data to get the games list
        $data = [
            'secureLogin' => $request->secureLogin,
            'options' => $request->options
        ];

         // Generate hash for secure communication
        $calculateHash = Misc::CalculateHash($data);

        $data['hash'] = $calculateHash;

        // Set the API URL, fallback to default in case the env variable is missing
        $url = env('PRAGMATIC_PLAY_GETGAMEURL', "http://localhost/ISJ-tect-assessment/public/api/testGetGame");

        $response = Http::asForm()->post($url, $data);

        // Check if the request was successful and return the corresponding response
        if ($response->successful()) 
        {
            $gameList = json_decode($response->body(), true);

            foreach($gameList['gameList'] as $game)
            {
                // Loop through the games and update or create them in the database
                Game::updateOrCreate(
                    [
                        'provider_game_id' => $game['gameID']
                    ],
                    [
                        'name' => $game['gameName'],
                        'provider_id' => 1,
                        'type_id' => $game['gameTypeID'],
                        'type_description' => $game['typeDescription'],
                        'technology' => $game['technology'],
                        'platform' => $game['platform'],
                        'demo' => $game['demoGameAvailable'],
                        'aspect_ratio' => $game['aspectRatio'],
                        'technology_id' => $game['technologyID'],
                        'game_id_numeric' => $game['gameIdNumeric'],
                        'frb_available' => $game['frbAvailable'],
                        'variable_frb_available' => $game['variableFrbAvailable'],
                        'lines' => $game['lines'],
                        'data_type' => $game['dataType'],
                        'jurisdictions' => $game['jurisdictions'],
                        'features' => $game['features'],
                        'status' => Constants::GAME_STATUS_ENABLED,
                    ]
                );
            }

            return response()->json([
                'message' => 'Request was successful',
                'status' => $response->status(),
                'data' => json_decode($response->body()),
            ]);
        }
        else
        {
            return response()->json([
                'message' => 'Request failed',
                'status' => $response->status(),
                'data' => json_decode($response->body()),
            ]);
        }
    }
}
