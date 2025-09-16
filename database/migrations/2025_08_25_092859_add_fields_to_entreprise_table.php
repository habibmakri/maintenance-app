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
            $table->string('activity')->after('name');
            $table->string('gerant')->after('activity');
            $table->string('adresse')->after('gerant');
            $table->string('phone')->unique()->after('adresse');
            $table->string('email')->unique()->after('phone');
            $table->string('password')->after('email');
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
