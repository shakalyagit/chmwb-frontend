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
        Schema::create('application_reasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_head_id')->constrained('application_heads')->onDelete('cascade');
            $table->string('reason_id'); // e.g., 'change-surname', 'cancel-reg'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_reasons');
    }
};
