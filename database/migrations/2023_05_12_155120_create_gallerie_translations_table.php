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
        Schema::create('gallerie_translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['gallerie_id', 'locale']);
            $table->unsignedBigInteger('gallerie_id');
            $table->foreign('gallerie_id')->references('id')->on('galleries')->cascadeOnDelete();;
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
        Schema::dropIfExists('gallerie_translations');
    }
};
