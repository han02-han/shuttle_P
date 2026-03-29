<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek kredensial email & password
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah.'
            ], 401);
        }

        $user = User::where('email', $request->email)->first();

        // 3. Pastikan yang login hanya Supir atau Orang Tua
        if (!in_array($user->role, ['driver', 'parent'])) {
            // Jika admin mencoba login lewat HP, tolak aksesnya
            $request->user()->currentAccessToken()->delete() ?? null; 
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak. Aplikasi ini khusus untuk Supir dan Orang Tua.'
            ], 403);
        }

        // 4. Buat Token
        $token = $user->createToken('mobile_token')->plainTextToken;

        // 5. Kirim balasan ke aplikasi Flutter
        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        // Hapus token agar sesi login di HP berakhir
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil logout'
        ], 200);
    }
}