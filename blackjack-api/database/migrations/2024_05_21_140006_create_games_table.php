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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('user_uuid')->constrained('users', 'uuid')->cascadeOnDelete();
            $table->foreignId('deck_id')->constrained();
            $table->json('player_hand')->nullable();
            $table->json('dealer_hand')->nullable();
            $table->unsignedTinyInteger('player_score')->nullable();
            $table->unsignedTinyInteger('dealer_score')->nullable();
            $table->enum('result', ['win', 'lose', 'tie'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
