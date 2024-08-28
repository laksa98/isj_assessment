<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use App\Helpers\Constants;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\Game;


class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'providerId' => [
                'required',
                Rule::exists(Provider::class, 'name')
                ->where('status', Constants::PROVIDER_STATUS_ACTIVE),
            ],
            'userId' => [
                'required',
                Rule::exists(User::class, 'id')
                ->whereNull('deleted_at'),
            ],
            'gameId' => [
                'required',
                Rule::exists(Game::class, 'provider_game_id')
            ],
            'roundId' => [
                'required',
            ],
            'amount' => [
                'required',
                'decimal:2',
                'min:0',
            ],
            'reference' => [
                'required',
            ],
            'roundDetails' => [
                'required',
            ]
        ];
        return $rules;
    }

    /**
     * Handle a failed validation attempt.
     * 
     * This method is triggered when validation fails. 
     * Customized the response to return specific error codes and descriptions based on the failed validation.
     *
     */
    public function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();

        if ($errors->has('userId')) 
        {
            $response = [
                "error" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT_CODE,
                "description" => Constants::RESPONSE_PLAYER_NOT_FOUND_OR_IS_LOGGED_OUT,
            ];
        }
        else if ($errors->has('gameId')) 
        {
            $response = [
                'error' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED_CODE,
                'description' => Constants::RESPONSE_GAME_NOT_FOUND_OR_DISABLED
            ];
        }
        else 
        {
            $response = [
                "error" => Constants::RESPONSE_BAD_PARAMETER_CODE,
                "description" => Constants::RESPONSE_BAD_PARAMETER,
            ];
        }

        throw new HttpResponseException(response()->json($response));
    }
}
