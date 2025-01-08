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
        Schema::create('used_pieces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fichepanne_id');
            $table->unsignedBigInteger('piece_id');
            $table->float('quantité');
            $table->timestamps();
            $table->foreign('fichepanne_id')->references('id')->on('fichepanne')->onDelete('cascade');
            $table->foreign('piece_id')->references('id')->on('pieces_maintenance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('used_pieces');
    }
    
};
