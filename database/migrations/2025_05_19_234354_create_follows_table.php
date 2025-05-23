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
        Schema::create('follows', function (Blueprint $table) {
           Schema::create('follows', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');         // الشخص اللي يتابع
    $table->unsignedBigInteger('followed_user_id'); // الشخص اللي يتم متابعته
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->foreign('followed_user_id')->references('id')->on('users')->onDelete('cascade');

    $table->unique(['user_id', 'followed_user_id']); // لمنع تكرار نفس العلاقة
});

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
