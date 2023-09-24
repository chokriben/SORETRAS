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
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->integer('active')->default(0);
            $table->string('num_telephone');
            $table->string('email');
            $table->string('cin')->unique();
            $table->string('jour_naissance');
            $table->string('mois_naissance');
            $table->string('annee_naissance');
            $table->string('password');
            $table->string('code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parents');
    }
};
