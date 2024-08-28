<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Provider;
use App\Models\Game;
use Illuminate\Http\Response;
use App\Helpers\Misc;
use App\Helpers\Constants;

class RefundControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic unit test example.
     */
    public function testRefund()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id, 'provider_id' => $provider->id, 'game_id' => $game->id, 'type' => 'bet']);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'reference' => $transaction->reference,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/refund', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'transactionId' => $response->json('transactionId'),
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                 ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'reference' => $requestData['reference'],
            'type' => 'refund',
        ]);
    }

    public function testRefundUserNotFound()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id, 'provider_id' => $provider->id, 'game_id' => $game->id, 'type' => 'bet']);

        $requestData = [
            'userId' => 1,
            'providerId' => $provider->name,
            'reference' => $transaction->reference,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/refund', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT_CODE,
                    "description" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT,
                ]);
    }

    public function testRefundUserFrozen()
    {
        $user = User::factory()->create(['status' => 'frozen']);
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id, 'provider_id' => $provider->id, 'game_id' => $game->id, 'type' => 'bet']);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'reference' => $transaction->reference,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/refund', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_PLAYER_IS_FROZEN_CODE,
                    'description' => Constants::RESPONSE_PLAYER_IS_FROZEN
                ]);
    }

    public function testRefundTransactionNotExist()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id, 'provider_id' => $provider->id, 'game_id' => $game->id, 'type' => 'bet']);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'reference' => "test",
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/refund', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                ]);
    }

    public function testRefundProcessed()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id, 'provider_id' => $provider->id, 'game_id' => $game->id, 'type' => 'refund']);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'reference' => $transaction->reference,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/refund', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                ]);
    }

    public function testRefundBadParameter()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);
        $transaction = Transaction::factory()->create(['user_id' => $user->id, 'provider_id' => $provider->id, 'game_id' => $game->id, 'type' => 'refund']);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->id,
            'reference' => $transaction->reference,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/refund', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_BAD_PARAMETER_CODE,
                    "description" => Constants::RESPONSE_BAD_PARAMETER,
                ]);
    }
}
