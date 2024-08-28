<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;

class UserController extends Controller
{
    public function index (Request $request)
    {
        return response()->json($request, 201);
    }
    public function store (Request $request)
    {
        // return response()->json($request, 201);
        $user = User::create([
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
            'username' => $request->username,
            'status' => $request->status,
        ]);

        $userWallet = Wallet::create([
            'user_id' => $user->id,
            'currency' => $request->currency,
            'balance' => $request->balance,
            'bonus' => $request->bonus,
        ]);

        return response()->json([$user, $userWallet], 201);
    }
}
