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
            $table->json('notes')->after('session_id')->nullable();
        });
        Schema::table('transport_personne', function (Blueprint $table) {
            $table->json('notes')->after('session_id')->nullable();
        });
        Schema::table('transport_marchandise', function (Blueprint $table) {
            $table->json('notes')->after('session_id')->nullable();
        });
        Schema::table('transport_danger', function (Blueprint $table) {
            $table->json('notes')->after('session_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('tables', function (Blueprint $table) {
        //     //
        // });
    }
};
