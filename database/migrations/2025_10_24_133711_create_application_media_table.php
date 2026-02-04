<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('application_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_head_id')->constrained('application_heads')->onDelete('cascade');
            $table->string('document_type'); // e.g., 'photo', 'dobProof', 'regCert'
            $table->string('url');
            $table->string('original_name');
            $table->string('ext', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_media');
    }
};
