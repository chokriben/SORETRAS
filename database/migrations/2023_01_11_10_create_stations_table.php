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
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(1);

            $table->string('latitude')->default('');
            $table->string('longitude')->default('');
            $table->integer('code');
            $table->unsignedBigInteger('gare_id');
            $table->foreign('gare_id')->references('id')->on('gares')->onDelete('cascade');
            $table->unsignedBigInteger('ligne_id');
            $table->foreign('ligne_id')->references('id')->on('lignes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stations');
    }
};
