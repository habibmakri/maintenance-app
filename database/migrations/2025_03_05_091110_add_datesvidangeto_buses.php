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
        Schema::table('buses', function (Blueprint $table) {
            $table->date('vidange_moteur_date')->nullable()->after('derniervidange');
            $table->date('vidange_boite_date')->nullable()->after('derniervidangeboite');
            $table->date('vidange_pond_date')->nullable()->after('derniervidangepond'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buses', function (Blueprint $table) {
            //
        });
    }
};
