<?php

namespace App\Helpers;

use ReflectionClass;
use Illuminate\Support\Arr;

class Constants
{
    // User account status constants
    const ACCOUNT_STATUS_ACTIVE = 'active'; // The account is active
    const ACCOUNT_STATUS_INACTIVE = 'inactive'; // The account is inactive
    const ACCOUNT_STATUS_FROZEN = 'frozen'; // The account is frozen

    // Provider status constants
    const PROVIDER_STATUS_ACTIVE = 'active'; // The provider is active
    const PROVIDER_STATUS_INACTIVE = 'inactive'; // The provider is inactive

    // Game status constants
    const GAME_STATUS_ENABLED = 'enabled'; // The game is enabled and available
    const GAME_STATUS_DISABLED = 'disabled'; // The game is disabled and unavailable

    // Transaction type constants
    const TRANSACTION_TYPE_BET = 'bet'; // The transaction is a bet
    const TRANSACTION_TYPE_RESULT = 'result'; // The transaction is a result
    const TRANSACTION_TYPE_REFUND = 'refund'; // The transaction is a refund

    // Transaction status constants
    const TRANSACTION_STATUS_PROCESSING = 'processing'; // The transaction is still processing
    const TRANSACTION_STATUS_COMPLETED = 'completed'; // The transaction is completed

    // API response descriptions and codes
    const RESPONSE_SUCCESS = 'Success'; // Response for a successful operation
    const RESPONSE_SUCCESS_CODE = 0; // Success response code
    const RESPONSE_INSUFFICIENT_BALANCE = 'Insufficient Balance'; // Response for insufficient balance
    const RESPONSE_INSUFFICIENT_BALANCE_CODE = 1; // Insufficient balance code
    const RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT = 'Player not found or is logged out'; // Player not found or logged out
    const RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT_CODE = 2; // Player not found or logged out code
    const RESPONSE_BET_IS_NOT_ALLOWED = 'Bet is not allowed'; // Bet is not allowed response
    const RESPONSE_BET_IS_NOT_ALLOWED_CODE = 3; // Bet not allowed response code
    const RESPONSE_PLAYER_AUTHENTICATION_FAILED = 'Player authentication failed due to invalid, not found or expired token'; // Player authentication failed
    const RESPONSE_PLAYER_AUTHENTICATION_FAILED_CODE = 4; // Player authentication failed response code
    const RESPONSE_INVALID_HASH_CODE = 'Invalid hash code'; // Invalid hash code response
    const RESPONSE_INVALID_HASH_CODE_CODE = 5; // Invalid hash code response code
    const RESPONSE_PLAYER_IS_FROZEN = 'Player is frozen'; // Player account is frozen
    const RESPONSE_PLAYER_IS_FROZEN_CODE = 6; // Player frozen response code
    const RESPONSE_BAD_PARAMETER = 'Bad parameters in the request, please check post parameters'; // Bad parameters in request
    const RESPONSE_BAD_PARAMETER_CODE = 7; // Bad parameter response code
    const RESPONSE_GAME_NOT_FOUND_OR_DISABLED = 'Game is not found or disabled'; // Game not found or disabled
    const RESPONSE_GAME_NOT_FOUND_OR_DISABLED_CODE = 8; // Game not found/disabled response code
    const RESPONSE_BET_LIMIT_HAS_BEEN_REACHED = 'Bet limit has been reached'; // Bet limit reached
    const RESPONSE_BET_LIMIT_HAS_BEEN_REACHED_CODE = 50; // Bet limit reached response code

    // Internal server error response messages and codes
    const RESPONSE_INETERNAL_SERVER_ERROR_RETRY = 'Internal server error'; // Server error (retry allowed)
    const RESPONSE_INETERNAL_SERVER_ERROR_RETRY_CODE = 100; // Internal server error code (retry)
    const RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY = 'Internal server error'; // Server error (no retry allowed)
    const RESPONSE_INETERNAL_SERVER_ERROR_NO_RETRY_CODE = 120; // Internal server error code (no retry)
    const RESPONSE_INTERNAL_SERVER_ERROR_ON_ENDROUND_PROCESSING = 'Internal server error on EndRound processing'; // Error during end round processing
    const RESPONSE_INTERNAL_SERVER_ERROR_ON_ENDROUND_PROCESSING_CODE = 130; // Error during end round processing response code
    
    // Reality check warnings and limits
    const RESPONSE_REALITY_CHECK_WARNING = 'Reality check warning'; // Reality check warning
    const RESPONSE_REALITY_CHECK_WARNING_CODE = 210; // Reality check warning code
    const RESPONSE_PLAYER_BET_OUT_OF_LIMITS = "Player's bet out of his bet limits"; // Player's bet exceeded limits
    const RESPONSE_PLAYER_BET_OUT_OF_LIMITS_CODE = 310; // Bet out of limits response code

}

?>