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
            $table->foreignId('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->string('provider_game_id');
            $table->string('name');
            $table->string('type_id');
            $table->string('type_description');
            $table->string('technology');
            $table->string('platform');
            $table->boolean('demo');
            $table->string('aspect_ratio');
            $table->string('technology_id');
            $table->bigInteger('game_id_numeric');
            $table->boolean('frb_available');
            $table->boolean('variable_frb_available');
            $table->integer('lines');
            $table->string('data_type');
            $table->json('jurisdictions');
            $table->json('features')->nullable();
            $table->string('status');
            $table->timestamps();
            $table->softDeletes();
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
