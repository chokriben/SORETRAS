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
        Schema::create('gare_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['gare_id', 'locale']);
            $table->unsignedBigInteger('gare_id');
            $table->foreign('gare_id')->references('id')->on('gares')->cascadeOnDelete();;
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
        Schema::dropIfExists('gare_translations');
    }
};