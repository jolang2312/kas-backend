<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->string('keterangan');
            $table->bigInteger('jumlah');
            $table->string('status')->default('pending'); // pending, settlement, expire, cancel
            $table->string('payment_type')->nullable();   // qris, bank_transfer, gopay
            $table->timestamp('transaction_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};