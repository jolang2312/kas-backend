<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        // Mengambil semua user dengan role anggota
        return response()->json(User::where('role', 'anggota')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'anggota',
        ]);

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function destroy($id)
    {
        User::destroy($id);
        return response()->json(['status' => 'success']);
    }
}