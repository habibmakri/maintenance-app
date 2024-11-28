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
        Schema::create('fiches_maintenance', function (Blueprint $table) {
            $table->id();
            $table->date('date_fiche'); 
            $table->unsignedBigInteger('id_bus'); 
            $table->unsignedBigInteger('id_ligne'); 
            $table->float('gasoile'); 
            $table->float('kmdepart');
            $table->float('kmarrive');
            $table->float('kmhlp');
            $table->float('kmgobale');
            $table->float('kmcommerciale');
            $table->timestamps();

            
            $table->foreign('id_bus')->references('id')->on('buses')->onDelete('cascade'); 
            $table->foreign('id_ligne')->references('id')->on('lignes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fiches_maintenance');
    }
};
