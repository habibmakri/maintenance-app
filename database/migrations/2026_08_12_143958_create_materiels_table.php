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
        Schema::create('emplacements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');    
            $table->timestamps();
        });
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_emplacements');
            $table->string('numinventaire');
            $table->string('designation');
            $table->string('observation');
            $table->integer('etat');
            $table->timestamps();
            $table->foreign('id_emplacements')->references('id')->on('emplacements')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};
