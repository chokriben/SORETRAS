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
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('code_a_bare')->nullable();
            $table->string('cin')->unique();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->float('tarif')->nullable();
            $table->boolean('is_vdf')->default(false);
            $table->boolean('is_free')->default(false);
            $table->date('date_reception')->nullable();
            $table->string('status')->default('draft');
          // $table->foreignId('visiteur_id')->constrained();
           // $table->foreignId('visiteur_id')->references('id')->on('visiteurs')->cascadeOnDelete()->cascadeOnUpdate();
           // $table->foreign('type_abonnement_id')->references('id')->on('type_abonnements')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('type_abonnement_id');
            $table->foreign('type_abonnement_id')->references('id')->on('type_abonnements')->onDelete('cascade');
            // $table->foreign('circuit_id')->references('id')->on('circuits')->cascadeOnDelete()->cascadeOnUpdate();
            //$table->foreign('impression_id')->references('id')->on('impressions')->cascadeOnDelete()->cascadeOnUpdate();
            //$table->foreign('duree_abonnement_id')->references('id')->on('dureeAbonnements')->cascadeOnDelete()->cascadeOnUpdate();
            //$table->foreign('ligne_id')->references('id')->on('ligne')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnements');
    }
};
