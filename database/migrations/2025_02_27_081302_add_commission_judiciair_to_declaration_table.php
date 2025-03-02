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
        Schema::table('declaration_judiciaire', function (Blueprint $table) {
            $table->unsignedBigInteger('commission_id')->nullable()->after('adverse');
            $table->foreign('commission_id')->references('id')->on('commission_judiciaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('declaration_judiciaire', function (Blueprint $table) {
            //
        });
    }
};
