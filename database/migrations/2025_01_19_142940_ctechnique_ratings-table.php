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
        Schema::create('ctechnique_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('service');
            $table->string('controler');
            $table->string('clean');
            $table->string('order');
            $table->string('message');
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctechnique_ratings');

    }
};
