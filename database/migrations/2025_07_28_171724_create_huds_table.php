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
        Schema::create('huds', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable();
            $table->string('version')->unique()->nullable();
            $table->string('Name')->nullable();
            $table->string('main_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('team_a_color')->nullable();
            $table->string('team_b_color')->nullable();
            $table->boolean('cover')->default(true);
            $table->boolean('players')->default(true);
            $table->boolean('game_format')->default(true);
            $table->boolean('side_swap_alert')->default(true);
            $table->boolean('team_statistic')->default(true);
            $table->boolean('bracket')->default(false);
            $table->boolean('map_pick')->default(true);
            $table->boolean('player_cam')->default(false);
            $table->boolean('caster_cam')->default(false);
            $table->boolean('caster')->default(false);
            $table->boolean('win')->default(true);
            $table->boolean('replay')->default(true);
            $table->boolean('time_out')->default(true);
            $table->boolean('result')->default(false);
            $table->boolean('group_stage')->default(false);
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('huds');
    }
};
