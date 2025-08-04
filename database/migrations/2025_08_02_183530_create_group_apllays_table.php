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
        Schema::create('group_apllays', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId("student_id")->references("id")->on("students");
            $table->foreignId("group_id")->references("id")->on("groups");
            $table->foreignId("skill_id")->references("id")->on("skills");
            $table->foreignId("post_id")->references("id")->on("group_posts")->onDelete("cascade");
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_apllays');
    }
};
