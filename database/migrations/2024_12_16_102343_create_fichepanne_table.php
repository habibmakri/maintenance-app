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
        Schema::create('fichepanne', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fichemaintenance_id');
            $table->unsignedBigInteger('pannnename_id');
            $table->boolean('solved');
            $table->timestamps();
            $table->foreign('fichemaintenance_id')->references('id')->on('fiches_maintenance')->onDelete('cascade');
            $table->foreign('pannnename_id')->references('id')->on('pannenames')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichepanne');
    }
};
