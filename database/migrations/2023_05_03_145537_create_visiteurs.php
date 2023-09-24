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
        Schema::create('visiteurs', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(1);
            $table->string('identifiant_ministere')->unique();
            $table->date('date_naissance');
            $table->string('num_telephone');
            $table->string('photo_url')->nullable();
            $table->string('email')->unique();
            $table->string('cin')->unique();
            $table->date('date_emission');
            $table->string('cin_parent')->unique();
            $table->unsignedBigInteger('Etablissement_id');
            $table->foreign('Etablissement_id')->references('id')->on('etablissements')->onDelete('cascade');
            $table->unsignedBigInteger('TypeAbonne_id');
            $table->foreign('TypeAbonne_id')->references('id')->on('type_abonnes')->onDelete('cascade');
            $table->unsignedBigInteger('Abonnement_id');
            $table->foreign('Abonnement_id')->references('id')->on('abonnements')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visiteurs');
    }
};
