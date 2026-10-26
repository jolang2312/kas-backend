<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where(
            'email',
            $credentials['email']
        )->first();

        if (
            !$user ||
            !Hash::check(
                $credentials['password'],
                $user->password
            )
        ) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }


        // Hapus token lama
        $user->tokens()->delete();


        // Buat token baru
        $token = $user
            ->createToken('auth_token')
            ->plainTextToken;


        return response()->json([

            'message' => 'Login berhasil',

            'access_token' => $token,

            'token_type' => 'Bearer',

            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ?? 'anggota',
            ],

        ]);
    }
}