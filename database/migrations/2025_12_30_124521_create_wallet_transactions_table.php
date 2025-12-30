<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('type', ['credit', 'debit']);
            $table->bigInteger('amount');

            $table->string('source')->nullable(); 
            // contoh: topup_midtrans, shortlink_premium, otp_access

            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();

            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['related_type', 'related_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
