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
        Schema::create('application_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_head_id')->constrained('application_heads')->onDelete('cascade');

            // Personal Information
            $table->string('name');
            $table->string('father_name');
            $table->text('address');
            $table->string('district');
            $table->string('pincode');
            $table->string('police_station');
            $table->string('aadhaar');
            $table->date('dob')->nullable();
            $table->string('blood_group')->nullable();

            // Contact Information
            $table->string('mobile');
            $table->string('email');

            // Academic & Registration Information
            $table->string('reg_number')->nullable();
            $table->date('reg_date')->nullable();
            $table->string('qualification')->nullable();
            $table->string('examination')->nullable();
            $table->string('held_in')->nullable();
            $table->string('university');
            $table->string('college');
            $table->string('final_roll_no')->nullable();
            $table->string('term')->nullable();
            $table->string('university_reg_no')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_details');
    }
};
