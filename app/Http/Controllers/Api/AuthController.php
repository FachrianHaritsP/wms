<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        if(!Auth::attempt([

            'email' => $request->email,
            'password' => $request->password

        ])){

            return response()->json([

                'success' => false,
                'message' => 'Invalid credentials'

            ], 401);

        }

        $user = Auth::user();

        //dd(method_exists($user, 'createToken'));
        $token = $user->createToken(
            'flutter-token'
        )->plainTextToken;

        return response()->json([

            'success' => true,
            'token' => $token,
            'user' => $user

        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'success' => true,
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout success'
        ]);
    }


}
