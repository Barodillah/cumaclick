<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topups', function (Blueprint $table) {
            $table->id();

            // Relasi user
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Identitas transaksi Midtrans
            $table->string('order_id')->unique(); // wajib unik
            $table->string('transaction_id')->nullable(); // dari Midtrans
            $table->string('payment_type')->nullable(); // gopay, bank_transfer, etc

            // Nominal
            $table->integer('gross_amount'); // nilai topup (rupiah)

            // Status transaksi Midtrans
            $table->string('transaction_status')->nullable();
            $table->string('fraud_status')->nullable();

            // Waktu dari Midtrans
            $table->timestamp('transaction_time')->nullable();
            $table->timestamp('settlement_time')->nullable();
            $table->timestamp('expiry_time')->nullable();

            // Payload mentah (audit & debugging)
            $table->json('payload')->nullable();

            // Flag penting (anti double credit)
            $table->boolean('is_processed')->default(false);

            $table->timestamps();

            // Index penting
            $table->index(['user_id']);
            $table->index(['transaction_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topups');
    }
};
