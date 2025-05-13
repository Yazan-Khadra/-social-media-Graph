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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // registeration
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string("birth_date");
            // profile informations
            $table->string('email')->nullable()->unique();
            $table->string('mobile_number')->nullable()->unique();
            $table->string('password');
            $table->string('profile_image')->nullable();
            $table->string("bio")->nullable();
            $table->string("cv_url")->nullable();
            $table->json("social_links")->nullable();
            // role
            $table->string('role')->default("student");
            //    foreign keys
            // $table->foreignId("year_id")->references("id")->on("years");
            // $table->foreignId("specialization_id")->references("id")->on("specializations");
            $table->timestamp('email_verified_at')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
