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
        Schema::table('fichepanne', function (Blueprint $table) {
            $table->date('date_resoudre')->nullable()->after('solved');
            $table->string('lieu_resoudre')->nullable()->after('date_resoudre');
            $table->string('brigade')->nullable()->after('lieu_resoudre');
            $table->json(column: 'equipe')->nullable()->after('brigade');
            $table->text(column: 'description')->nullable()->after('equipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fichepanne', function (Blueprint $table) {
            //
        });
    }
};
