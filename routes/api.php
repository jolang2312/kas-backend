<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransactionController;

// Auth Route
Route::post('/login', [AuthController::class, 'login']);

// Access Anggota & Admin
Route::get('/summary', [TransactionController::class, 'summary']);
Route::get('/pemasukan', [TransactionController::class, 'getRiwayat']);
Route::post('/pembayaran', [TransactionController::class, 'bayar']);

// Access Khusus Admin (CRUD Anggota & Bukti Transfer)
Route::get('/anggota', [AnggotaController::class, 'index']);
Route::post('/anggota', [AnggotaController::class, 'store']);
Route::delete('/anggota/{id}', [AnggotaController::class, 'destroy']);