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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->json('reasons'); // Application reasons
            
            // Personal Information
            $table->string('name');
            $table->string('father_name');
            $table->text('address');
            $table->string('district');
            $table->string('pincode');
            $table->string('police_station');
            $table->string('aadhaar');
            $table->string('dob');
            $table->string('blood_group')->nullable();
            
            // Contact Information
            $table->string('mobile');
            $table->string('email');
            
            // Academic & Registration Information
            $table->string('reg_number')->nullable();
            $table->string('reg_date')->nullable();
            $table->string('qualification');
            $table->string('examination');
            $table->string('held_in');
            $table->string('university');
            $table->string('college');
            $table->string('final_roll_no')->nullable();
            $table->string('term')->nullable();
            $table->string('university_reg_no')->nullable();
            
            // File uploads (store file paths)
            $table->json('uploaded_files')->nullable();
            
            // Application status
            $table->string('status')->default('pending');
            $table->string('reference_id')->unique();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
