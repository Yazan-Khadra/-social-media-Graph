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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("description");
            $table->json('files')->nullable();
            $table->string("title")->nullable();
             $table->enum('privacy', ['public', 'followers', 'private'])->default('public');
             $table->foreignId('admin_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreignId("project_id")
            ->nullable()
            ->references("id")->on("projects")
            ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
