<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        return User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'document' => preg_replace('/[^0-9]/', '', $request->document),
            'profile_id' => $request->profile_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }

    public function resetPassword(request $request) {
        return User::where('id', $request->id)->update([
            'password' => Hash::make($request->document),
        ]);
    }

    public function deleteUser(request $request) {
        return User::where('id', $request->id)->delete();
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function isAuth()
    {
        return response()->json('Você não esta Autenticado e sera redirecionado ao login');
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
