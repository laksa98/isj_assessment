<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Provider;
use App\Models\Game;
use Illuminate\Http\Response;
use App\Helpers\Misc;
use App\Helpers\Constants;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    /**
     * A basic unit test example.
     */
    public function testBet()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'transactionId' => $response->json('transactionId'),
                    'currency' => $user->wallet->currency,
                    'cash' => $user->wallet->balance,
                    'bonus' => $user->wallet->bonus,
                    'usedPromo' => 0,
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                 ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'reference' => $requestData['reference'],
            'type' => 'bet',
        ]);
    }

    public function testBetInvalidHash()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = "test";

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_INVALID_HASH_CODE_CODE,
                    "desciption" => Constants::RESPONSE_INVALID_HASH_CODE,
                 ]);
    }

    public function testBetUserNotFound()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'disabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => 999,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT_CODE,
                    "description" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT,
                 ]);
    }

    public function testBetUserFrozen()
    {
        $user = User::factory()->create(['status' => 'frozen']);
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'disabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_PLAYER_IS_FROZEN_CODE,
                    'description' => Constants::RESPONSE_PLAYER_IS_FROZEN
                 ]);
    }

    public function testBetGameDisabled()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'disabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED_CODE,
                    'description' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED
                 ]);
    }

    public function testBetBadParameter()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->id,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_BAD_PARAMETER_CODE,
                    "description" => Constants::RESPONSE_BAD_PARAMETER,
                 ]);
    }

    public function testBetInsufficientfund()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id, 'balance' => 0]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/bet', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_INSUFFICIENT_BALANCE_CODE,
                        'description' => Constants::RESPONSE_INSUFFICIENT_BALANCE
                 ]);
    }

    public function testResult()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/result', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'transactionId' => $response->json('transactionId'),
                    'currency' => $user->wallet->currency,
                    'cash' => $user->wallet->balance,
                    'bonus' => $user->wallet->bonus,
                    'error' => Constants::RESPONSE_SUCCESS_CODE,
                    'description' => Constants::RESPONSE_SUCCESS,
                 ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'reference' => $requestData['reference'],
            'type' => 'result',
        ]);
    }

    public function testResultInvalidHash()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = "test";

        $response = $this->post('/api/result', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_INVALID_HASH_CODE_CODE,
                    "desciption" => Constants::RESPONSE_INVALID_HASH_CODE,
                 ]);
    }

    public function testResultUserNotFound()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => 1,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/result', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT_CODE,
                    "description" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT,
                 ]);
    }

    public function testResultUserFrozen()
    {
        $user = User::factory()->create(['status' => 'frozen']);
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/result', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_PLAYER_IS_FROZEN_CODE,
                    'description' => Constants::RESPONSE_PLAYER_IS_FROZEN
                 ]);
    }

    public function testResultGameDisabled()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'disabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->name,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/result', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    'error' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED_CODE,
                    'description' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED
                 ]);
    }

    public function testResultBadParameter()
    {
        $user = User::factory()->create();
        $provider = Provider::factory()->create();
        $game = Game::factory()->create(['provider_id' => $provider->id, 'status' => 'enabled']);
        $wallet = Wallet::factory()->create(['user_id' => $user->id]);

        $requestData = [
            'userId' => $user->id,
            'providerId' => $provider->id,
            'gameId' => $game->provider_game_id,
            'amount' => "10.00", 
            'reference' => $this->faker->uuid,
            'timestamp' => $this->faker->numberBetween(123456789, 234567890),
            'roundId' => $this->faker->numberBetween(1, 100),
            'roundDetails' => $this->faker->sentence,
        ];

        $calculateHash = Misc::CalculateHash($requestData);
        
        $requestData['hash'] = $calculateHash;

        $response = $this->post('/api/result', $requestData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                    "error" => Constants::RESPONSE_BAD_PARAMETER_CODE,
                    "description" => Constants::RESPONSE_BAD_PARAMETER,
                 ]);
    }
}
