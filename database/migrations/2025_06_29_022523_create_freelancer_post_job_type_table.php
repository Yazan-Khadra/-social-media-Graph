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
        Schema::create('freelancer_post_job_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('freelancer_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_type_id')->constrained()->onDelete('cascade');
<<<<<<< HEAD
            // $table->unique(['freelancer_post_id', 'work_place_id']);
=======
            $table->unique(['freelancer_post_id', 'job_type_id']);
>>>>>>> 9f306a0e01aa36de1e57827211eb5674df3c10b7
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('freelancer_post_job_type');
    }
};
