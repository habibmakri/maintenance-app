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
        Schema::create('declaration_judiciaire', function (Blueprint $table) {
            $table->id();
            $table->date('date_fiche'); 
            $table->boolean('caat')->default(false); 
            $table->boolean('paye')->default(false); 
            $table->unsignedBigInteger('id_bus'); 
            $table->unsignedBigInteger('id_chauffeur'); 
            $table->unsignedBigInteger('id_ligne')->nullable(); 
            $table->dateTime('time_day');
            $table->string('place');
            $table->text(column: 'description')->nullable();
            $table->text(column: 'pertes')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();


            $table->foreign('id_bus')->references('id')->on('buses')->onDelete('cascade'); 
            $table->foreign('id_chauffeur')->references('id')->on('chauffeurs')->onDelete('cascade');
            $table->foreign('id_ligne')->references('id')->on('lignes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declaration_judiciaire');
    }
};
