<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();

            // Relasi ke short link
            $table->foreignId('short_link_id')
                ->constrained('short_links')
                ->cascadeOnDelete();

            $table->string('short_code', 50)->index();

            // Identitas pengunjung
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();

            // Lokasi (optional – dari GeoIP)
            $table->string('country', 100)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Device & platform
            $table->string('device_type', 50)->nullable(); // desktop | mobile | tablet
            $table->string('device_brand', 50)->nullable();
            $table->string('device_model', 50)->nullable();

            $table->string('os', 50)->nullable();
            $table->string('os_version', 30)->nullable();

            $table->string('browser', 50)->nullable();
            $table->string('browser_version', 30)->nullable();

            // Informasi tambahan
            $table->boolean('is_bot')->default(false);
            $table->string('language', 20)->nullable();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();

            // Waktu klik
            $table->timestamp('clicked_at')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};

