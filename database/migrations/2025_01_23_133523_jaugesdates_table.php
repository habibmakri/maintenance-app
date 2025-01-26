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
        Schema::create('jaugesdates', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('type_id');
            $table->json(column: 'equipe')->nullable();
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('pannenames')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jaugesdates');
    }
};
