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
        Schema::create('abonne_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['abonne_id', 'locale']);
            $table->unsignedBigInteger('abonne_id');
            $table->foreign('abonne_id')->references('id')->on('abonnes')->cascadeOnDelete();;
            // Actual fields you want to translate
            $table->string('prenom')->nullable();
            $table->string('nom_parent')->nullable();
            $table->string('adresse')->nullable();
            $table->string('classe')->nullable();
            $table->string('profession')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonne_translations');
    }
};
