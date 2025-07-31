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
        Schema::create('battles', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('room_code')->unique()->nullable();
            $table->unsignedBigInteger('team_a_id');
            $table->string('map_a')->nullable();
            $table->integer('score_team_a')->nullable();
            $table->unsignedBigInteger('team_b_id'); 
            $table->integer('score_team_b')->nullable();
            $table->string('map_b')->nullable();
            $table->string('map_decider')->nullable();
            $table->timestamp('match_datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('battles');
    }
};
