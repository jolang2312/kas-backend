<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransactionController;


// LOGIN
Route::post('/login', [
    AuthController::class,
    'login'
]);


// DATA KAS
Route::get('/summary', [
    TransactionController::class,
    'summary'
]);

Route::get('/pemasukan', [
    TransactionController::class,
    'getRiwayat'
]);

Route::post('/pembayaran', [
    TransactionController::class,
    'bayar'
]);


// CALLBACK MIDTRANS
Route::post('/midtrans/notification', [
    TransactionController::class,
    'notificationHandler'
]);


// ANGGOTA
Route::get('/anggota', [
    AnggotaController::class,
    'index'
]);

Route::post('/anggota', [
    AnggotaController::class,
    'store'
]);

Route::delete('/anggota/{id}', [
    AnggotaController::class,
    'destroy'
]);