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
        Schema::create('type_abonnement_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['type_abonnement_id', 'locale']);
            $table->unsignedBigInteger('type_abonnement_id');
            $table->foreign('type_abonnement_id')->references('id')->on('type_abonnements')->cascadeOnDelete();;
            // Actual fields you want to translate
            $table->string('labelle');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_abonnement_translations');
    }
};
