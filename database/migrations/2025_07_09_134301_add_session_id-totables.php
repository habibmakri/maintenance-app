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
        Schema::table('taxis', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->after('date_paiement')->nullable();
            $table->foreign('session_id')->references('id')->on('formation_sessions')->nullable();
        });
        Schema::table('transport_personne', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->after('entreprise_id')->nullable();
            $table->foreign('session_id')->references('id')->on('formation_sessions')->nullable();
        });
        Schema::table('transport_marchandise', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->after('entreprise_id')->nullable();
            $table->foreign('session_id')->references('id')->on('formation_sessions')->nullable();
        });
        Schema::table('transport_danger', function (Blueprint $table) {
            $table->unsignedBigInteger('session_id')->after('entreprise_id')->nullable();
            $table->foreign('session_id')->references('id')->on('formation_sessions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
