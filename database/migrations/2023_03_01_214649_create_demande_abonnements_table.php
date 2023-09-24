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
        Schema::create('demande_abonnements', function (Blueprint $table) {
            $table->id();
            $table->date('date_reception');
            $table->date('date_Cmd');
            $table->string('code_query');
            $table->string('status');
         //   $table->foreignId('gare_id')->references('id')->on('gares')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_abonnements');
    }
};
