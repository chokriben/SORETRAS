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
        Schema::create('presentation_translations', function (Blueprint $table) {
            $table->id();
        
            $table->string('locale')->index();
            // Foreign key to the main model
            $table->unique(['presentation_id', 'locale']);
            $table->unsignedBigInteger('presentation_id');
            $table->foreign('presentation_id')->references('id')->on('presentations')->cascadeOnDelete();;
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
        Schema::dropIfExists('presentation_translations');
    }
};
