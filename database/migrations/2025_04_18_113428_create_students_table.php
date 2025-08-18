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
        Schema::create('students', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();

            $table->timestamps();
            // registeration
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string("birth_date")->nullable();
             $table->string('profile_image_url')->nullable();
            $table->string("bio")->nullable();
            $table->string("cv_url")->nullable();
            $table->json("social_links")->nullable();
            $table->json("skills")->nullable();
            //    foreign keys
            $table->foreignId('year_id')->nullable()->constrained('years')->onDelete("set null");
            $table->foreignId('major_id')->nullable()->constrained('majors')->onDelete('set null');
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }
    
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
