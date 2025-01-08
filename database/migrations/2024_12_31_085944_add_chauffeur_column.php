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
        // Schema::table('fiches_maintenance', function (Blueprint $table) {
        //     $table->unsignedBigInteger('id_chauffeur')->nullable()->after('date_fiche'); 
           
        //     $table->foreign('id_chauffeur')
        //           ->references('id') 
        //           ->on('chauffeurs'); 
        // }); 
       }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
