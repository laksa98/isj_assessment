<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use App\Helpers\Misc;

class GameControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic unit test example.
     */
    public function testGetGameUrl()
    {
        $requestData = [
            'secureLogin' => 'TestOperator',
            'symbol' => 'testGame',
            'language' => 'EN',
            'token' => 'testToken',
            'externalPlayerId' => 1
        ];

        $response = $this->post('/api/generateGameUrl', $requestData);

        $responseData = $response->json();

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'message' => 'Request was successful',
                    'status' => Response::HTTP_OK,
                    'data' => $responseData['data'],
                 ]);
    }

    public function testGetGameUrlIncorrectLogin()
    {
        $requestData = [
            'secureLogin' => 'TestOperator1',
            'symbol' => 'testGame',
            'language' => 'EN',
            'token' => 'testToken',
            'externalPlayerId' => 1
        ];

        $response = $this->post('/api/generateGameUrl', $requestData);

        $responseData = $response->json();

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'message' => 'Request failed',
                    'status' => 400,
                    'data' => $responseData['data'],
                 ]);
    }

    public function testGetGameUrEmptyfield()
    {
        $requestData = [
            'symbol' => 'testGame',
            'language' => 'EN',
            'token' => 'testToken',
            'externalPlayerId' => 1
        ];

        $response = $this->post('/api/generateGameUrl', $requestData);

        $responseData = $response->json();

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'message' => 'Request failed',
                    'status' => 400,
                    'data' => $responseData['data'],
                 ]);
    }

    public function testGetGameUrIncorrectfield()
    {
        $requestData = [
            'secureLogin' => 'TestOperator1',
            'symbol' => 'testGame',
            'language' => 'EN',
            'token' => 'testToken',
            'externalPlayerId' => 2
        ];

        $response = $this->post('/api/generateGameUrl', $requestData);

        $responseData = $response->json();

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'message' => 'Request failed',
                    'status' => 400,
                    'data' => $responseData['data'],
                 ]);
    }
}
