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
        Schema::create('extincteur', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->nullable();
            $table->string('type');
            $table->boolean('bus');
            $table->string('affectation')->nullable();
            $table->date('date_recharge')->nullable();
            $table->date('date_expiration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extincteur');
    }
};
