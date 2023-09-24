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
        Schema::create('ligne_station', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ligne_id')->references('id')->on('lignes')->cascadeOnDelete();
            $table->foreignId('station_dep_id')->references('id')->on('stations')->cascadeOnDelete();
            $table->foreignId('station_fin_id')->references('id')->on('stations')->cascadeOnDelete();
            $table->foreignId('gare_id')->references('id')->on('gares')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ligne_station');
    }
};
