<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('payer_name');   
            $table->decimal('amount', 15, 2);
            $table->enum('payment_method', ['QRIS', 'DANA']);
            $table->foreignId('category_id')->nullable();
            $table->enum('transaction_type', ['pemasukan', 'pengeluaran']);
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->string('officer_name');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transactions');
    }
};