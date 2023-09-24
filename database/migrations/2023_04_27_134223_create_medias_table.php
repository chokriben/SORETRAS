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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->string('src');
            $table->integer('nbr_vues')->nullable();
            $table->string('legende')->nullable();
            $table->string('path')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->string('type')->nullable();
            $table->string('locale')->nullable();
            $table->unsignedBigInteger('foreinkey')->nullable();
            $table->timestamps();
            // Replace 'related_table_name' with the actual name of the related table
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medias');
    }
};
