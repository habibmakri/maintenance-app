<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::table('fiches_maintenance', function (Blueprint $table) {
            $table->string('brigade')->after('id_ligne'); 
            $table->time('heur_depart')->after('brigade'); 
            $table->time('heur_arrive')->after('heur_depart'); 
        });
    }

    public function down(): void
    {
        Schema::table('fiches_maintenance', function (Blueprint $table) {
            $table->dropColumn(['brigade', 'heur_depart', 'heur_arrive']); 
        });
    }
};
