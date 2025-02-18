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
            $table->string('adverse')->nullable()->after('place');
            $table->boolean('responsability')->after('adverse')->nullable()->default(null);
            $table->string('decision')->after('responsability')->nullable()->default(null);
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
