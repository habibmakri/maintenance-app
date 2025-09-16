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
        Schema::table('entreprise', function (Blueprint $table) {
            $table->string('nrc')->after('gerant')->nullable();
            $table->string('nif')->after('nrc')->nullable();
            $table->string('nis')->after('nif')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('entreprise', function (Blueprint $table) {
            //
        });
    }
};
