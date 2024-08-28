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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('game_id');
            $table->integer('round_id');
            $table->integer('amount')->unsigned(); // Ensure amount is non-negative
            $table->string('reference');
            $table->foreignId('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->string('timestamp');
            $table->string('round_details');
            $table->string('type');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
