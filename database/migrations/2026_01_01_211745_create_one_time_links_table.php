<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('one_time_links', function (Blueprint $table) {
            $table->id();

            $table->foreignId('short_link_id')
                ->constrained('short_links')
                ->cascadeOnDelete();

            // hash OTP server-side (tidak pernah dikirim ke user)
            $table->char('token_hash', 64)->unique();

            // kapan di-arm
            $table->timestamp('activated_at')->nullable();

            // kapan dipakai (1x)
            $table->timestamp('used_at')->nullable();

            // audit
            $table->string('used_ip', 45)->nullable();
            $table->text('used_ua')->nullable();

            $table->timestamps();

            $table->index(['short_link_id', 'used_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('one_time_links');
    }
};
