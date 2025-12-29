<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            // Scope user
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete();

            // Relasi ke short_links
            $table->foreignId('short_link_id')
                  ->constrained('short_links')
                  ->cascadeOnDelete();

            // Tag value
            $table->string('name');

            $table->timestamps();

            // Prevent duplicate tags per link per user
            $table->unique(['user_id', 'short_link_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
