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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female', 'other']); // For gender
            $table->string('mobile_number')->unique();
            $table->string('aadhar_card_no')->unique()->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('pin_code')->nullable();
            $table->foreignId('skill_id')->nullable()->constrained('skills')->onDelete('cascade');
            $table->foreignId('qualification_id')->nullable()->constrained('qualifications')->onDelete('cascade');
            $table->foreignId('experience_id')->nullable()->constrained('experiences')->onDelete('cascade');
            $table->decimal('current_salary', 10, 2)->nullable();
            $table->string('photo')->nullable(); // For storing photo file name
            $table->string('cv')->nullable(); // For storing CV file name
            $table->string('password')->nullable();
            $table->string('current_location')->nullable(); // You can use Google Maps API for this field
            $table->string('passing_year')->nullable(); // You can use Google Maps API for this field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
