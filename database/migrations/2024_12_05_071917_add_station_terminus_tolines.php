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
        Schema::table('lignes', function (Blueprint $table) {
            $table->unsignedBigInteger('station_id')->nullable()->after('name');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade');
            $table->float('terminus')->length(3)->default(0)->after('station_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lignes', function (Blueprint $table) {
            $table->dropForeign(['station_id']);
            $table->dropColumn('station_id');
            $table->dropColumn('Terminus');
        });
    }
};
