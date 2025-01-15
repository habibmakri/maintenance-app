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
        Schema::create('traveauxlibre', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('id_bus');
            $table->string('nature');
            $table->date('date_resoudre');
            $table->string('lieu_resoudre');
            $table->string('brigade');
            $table->json(column: 'equipe');
            $table->text(column: 'description')->nullable();
            $table->timestamps();
            $table->foreign('id_bus')->references('id')->on('buses')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traveauxlibre');
    }
};
