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
        Schema::create('appel_doffre_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['appel_doffre_id', 'locale']);
            $table->unsignedBigInteger('appel_doffre_id');
            $table->foreign('appel_doffre_id')->references('id')->on('appel_doffres')->cascadeOnDelete();;
            // Actual fields you want to translate
            $table->string('name');
            $table->longText('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appel_doffre_translations');
    }
};
