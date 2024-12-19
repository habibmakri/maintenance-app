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
        Schema::table('users', function (Blueprint $table) {
            $table->string('service')->nullable()->after('lastname');
            $table->string('poste')->nullable()->after('service');
            $table->string('telephone')->nullable()->after('poste');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('service');
            $table->dropColumn('poste');
            $table->dropColumn('telephone');
        });
    }
};
