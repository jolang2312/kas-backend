<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemasukanController extends Controller
{
    public function index()
    {
        try {
            $data = DB::table('transactions')->orderBy('created_at', 'desc')->get();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            // Jika ada masalah koneksi DB atau tabel belum siap, kembalikan array kosong (mencegah error 500)
            return response()->json([], 200);
        }
    }
}