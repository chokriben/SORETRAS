<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateabonnesprixTable extends Migration
{
    public function up()
    {
        Schema::create('abonnesprix', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(true);
            $table->decimal('prix', 10, 3);
            $table->string('nom')->nullable();

            $table->string('code')->nullable();
            $table->boolean('annuel')->default(false);

            $table->float('klm')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('abonnesprix');
    }
}
