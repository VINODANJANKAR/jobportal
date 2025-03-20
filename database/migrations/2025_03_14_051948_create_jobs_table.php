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
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('post_id');
            $table->date('post_date');
            $table->date('valid_up_to');
            $table->enum('post_type', ['Regular', 'Image']);
            $table->enum('job_type', ['On-Roll', 'Contractual', 'Temporary'])->default('On-Roll');
            $table->string('upload_image')->nullable();
            $table->string('position')->nullable();
            $table->string('company_name')->nullable();
            $table->text('job_description')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('location')->nullable();
            $table->foreignId('skill_id')->nullable()->constrained('skills')->onDelete('cascade');
            $table->foreignId('experience_id')->nullable()->constrained('experiences')->onDelete('cascade');
            $table->boolean('is_repost')->default(false);
            $table->unsignedBigInteger('original_post_id')->nullable();
            $table->date('repost_date')->nullable();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->foreignId('post_by_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreign('original_post_id')->references('id')->on('jobs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
