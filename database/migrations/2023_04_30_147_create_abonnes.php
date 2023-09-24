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
        Schema::create('abonnes', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(1);
            $table->date('date_naissance')->nullable();
            $table->string('num_telephone');
            $table->string('type_eleve')->nullable();
            $table->string('etat')->nullable();
            $table->string('prix')->nullable();
            $table->string('type_inscrit')->nullable();
            $table->string('type_institut')->nullable();
            $table->foreignId('type_zone')->nullable()->constrained('gares');
            $table->string('type_validite')->nullable();
            $table->string('type_periode')->nullable();
            $table->string('type_paiment')->nullable();
            $table->string('type_abonne')->nullable();
            $table->string('email')->nullable();
            $table->string('cin');
            $table->date('date_emission')->nullable();
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->unsignedBigInteger('Etablissement_id')->nullable();
            $table->foreign('Etablissement_id')->references('id')->on('etablissements')->onDelete('cascade');
            $table->unsignedBigInteger('ligne_id')->nullable();
            $table->foreign('ligne_id')->references('id')->on('lignes')->onDelete('cascade');
            $table->timestamps();
            $table->string('id_uniq')->nullable();
            $table->string('code')->nullable();
            $table->string('src')->nullable();
            $table->string('order_id')->nullable();
            $table->date('date_paiement')->nullable();
            $table->date('date_imprim')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abonnes');
    }
};
