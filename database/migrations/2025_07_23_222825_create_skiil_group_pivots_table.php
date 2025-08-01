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
        Schema::create('skiil_group_pivots', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            //group skill foreign id
            $table->foreignId("group_id")->references("id")->on("group_posts")->onDelete("cascade");
            //skill foreign id
            $table->foreignId("skill_id")->references("id")->on("skills")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skiil_group_pivots');
    }
};
