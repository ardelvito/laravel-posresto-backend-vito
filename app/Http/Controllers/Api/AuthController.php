<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // login
    public function login(Request $request)
    {
        //validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // check if user exist
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'User not found'
                ],
                404
            );
        }

        // check user password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Invalid login details'
                ],
                404
            );
        }

        // generate token if user exist & password correct
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json(
            [
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ],
            200
        );

        return view('pages.auth.login');
    }
}
