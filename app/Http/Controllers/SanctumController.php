<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SanctumController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return User
     */

    public function login(Request $request)
    {
        $credentials = request(['email','password']);
        if(!auth()->attempt($credentials))
        {
            return response()->json([
                'message' => 'Unauthorized'
            ],401);
        }

        $user = User::where('email', $request->email)->first();
 
        $authtoken = $user->createToken('auth-token')->plainTextToken;
        return response()->json([
            'access_token' => $authtoken
        ],200);
        
    }
}
