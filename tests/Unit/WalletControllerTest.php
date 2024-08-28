<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Provider;
use Illuminate\Http\Response;
use App\Helpers\Misc;
use App\Helpers\Constants;

class WalletControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic unit test example.
     */
    public function testBalance()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/balance', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "currency" => $wallet->currency,
                    "cash" => $wallet->balance,
                    "bonus" => $wallet->bonus,
                    "error" => Constants::RESPONSE_SUCCESS_CODE,
                    "description" => Constants::RESPONSE_SUCCESS,
                 ]);

        $this->assertDatabaseHas('wallets', [
            'id' => $wallet->id,
            'user_id' => $user->id,
        ]);
    }

    public function testBalanceUserNotFound()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => 1,
            'providerId' => $provider->name,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/balance', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT_CODE,
                    "description" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT,
                ]);

    }

    public function testBalanceBadParameter()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->id,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/balance', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_BAD_PARAMETER_CODE,
                    "description" => Constants::RESPONSE_BAD_PARAMETER,
                ]);

    }
}
