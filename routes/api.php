<?php

use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\ProviderController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\RefundController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyHash;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware(VerifyHash::class)->group(function() {
    Route::post('/balance', [WalletController::class, 'getBalance']);
    Route::post('/bet', [TransactionController::class, 'bet']);
    Route::post('/result', [TransactionController::class, 'result']);
    Route::post('/refund', [RefundController::class, 'refund']);
});

Route::post('/generateGameUrl', [GameController::class, 'generateGameUrl']);
Route::post('/getGame', [GameController::class, 'getGames']);
Route::post('/healthCheck', [ProviderController::class, 'healthCheck']);


Route::post('/testGetGameUrl', [TestController::class, 'testGetGameUrl']);
Route::post('/testGetGame', [TestController::class, 'testGetGame']);
Route::get('/testHealthCheck', [TestController::class, 'testHealthCheck']);


Route::post('users', [UserController::class, 'store']);
Route::post('providers', [ProviderController::class, 'store']);

