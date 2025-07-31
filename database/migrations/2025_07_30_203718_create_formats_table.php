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
        Schema::create('formats', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->enum('team_format', ['warefare', 'operation'])
                ->default('warefare');
            $table->string('win_condition')->nullable();
            $table->string('game_mode')->nullable();
            $table->string('game_1')->nullable();
            $table->string('game_2')->nullable();
            $table->string('game_3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formats');
    }
};
