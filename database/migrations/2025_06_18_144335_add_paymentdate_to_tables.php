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
        Schema::table('transport_personne', function (Blueprint $table) {
            $table->date('date_paiement')->nullable()->after('payment_number');
        });
        Schema::table('transport_marchandise', function (Blueprint $table) {
            $table->date('date_paiement')->nullable()->after('payment_number');
        });
        Schema::table('transport_danger', function (Blueprint $table) {
            $table->date('date_paiement')->nullable()->after('payment_number');
        });
        Schema::table('taxis', function (Blueprint $table) {
            $table->date('date_paiement')->nullable()->after('payment_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
