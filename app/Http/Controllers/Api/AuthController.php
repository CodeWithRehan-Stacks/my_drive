<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email|max:255',
            'password' => 'required|string|min:8',
            'plan'     => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'plan'     => $request->plan,
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'status'       => 'success',
            'message'      => 'User registered successfully',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $user
        ], 201);
    }

    public function logIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Invalid login credentials'
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'       => 'success',
            'message'      => 'Logged in successfully',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'data'         => $user
        ], 200);
    }



    public function logOut(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get the authenticated user profile.
     */
  public function userProfile(Request $request)
{
    $user = $request->user();

    // ERROR FIX: Agar user nahi mila toh yahin se wapas bhej do
    if (!$user) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthenticated user. Sahi token provide karein.'
        ], 401);
    }
    return response()->json([
        'status' => 'success',
        'data'   => $user
    ], 200);
    
    // Baqi ka code same rahega...
}
}