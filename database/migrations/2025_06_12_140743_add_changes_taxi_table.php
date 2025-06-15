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
        Schema::create('taxis_list', function (Blueprint $table) {
            $table->id();
            $table->integer('counter')->default(0); 
            $table->date('valid_date')->nullable(); 
            $table->timestamps();
        });
        Schema::table('taxis', function (Blueprint $table) {
            $table->string('ip_adress')->after('payment_number');
            $table->string('gender')->after('birthplace'); 
            $table->unsignedBigInteger('list')->after('rejet');
            $table->dateTime('inscription_time')->after('id'); 
            $table->string('nin')->unique()->after('prenom_fr'); 
            $table->foreign('list')->references('id')->on('taxis_list')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
