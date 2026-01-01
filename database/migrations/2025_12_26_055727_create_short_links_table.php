<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('short_links', function (Blueprint $table) {
            $table->id();

            // Owner
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // Core
            $table->string('short_code')->unique()->collation('utf8mb4_bin')->change();
            $table->string('custom_alias')->nullable()->unique()->collation('utf8mb4_bin')->change();

            // Destination
            $table->enum('destination_type', ['url', 'file'])->default('url');
            $table->text('destination_url')->nullable();
            $table->unsignedBigInteger('destination_file_id')->nullable();

            // Security
            $table->string('pin_code')->nullable();
            $table->string('password_hint')->nullable();
            $table->boolean('require_otp')->default(false);

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('one_time')->default(false);
            $table->timestamp('blocked_at')->nullable();
            $table->boolean('enable_ads')->default(false);

            // Time control
            $table->timestamp('active_from')->nullable();
            $table->timestamp('active_until')->nullable();
            $table->timestamp('expired_at')->nullable();

            // Click control
            $table->unsignedInteger('max_click')->nullable();
            $table->unsignedInteger('click_count')->default(0);
            $table->timestamp('last_clicked_at')->nullable();

            // Anti abuse
            $table->unsignedTinyInteger('abuse_score')->default(0);

            // Preview, ads & metadata
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('enable_preview')->default(false);
            $table->text('note')->nullable();
            $table->boolean('enable_ads')->default(true);

            // Creator info (anonymous friendly)
            $table->ipAddress('created_ip')->nullable();
            $table->text('created_ua')->nullable();

            $table->timestamps();

            // Indexing
            $table->index(['short_code', 'custom_alias']);
            $table->index(['is_active', 'expired_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
