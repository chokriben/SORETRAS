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
        Schema::create('visiteur_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['visiteur_id', 'locale']);
            $table->unsignedBigInteger('visiteur_id');
            $table->foreign('visiteur_id')->references('id')->on('visiteurs')->cascadeOnDelete();;
            // Actual fields you want to translate
            $table->string('nom');
            $table->string('prenom');
            $table->string('nom_parent');
            $table->string('prenom_parent');
            $table->string('adresse');
            $table->string('classe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visiteur_translations');
    }
};
