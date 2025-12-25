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
        Schema::create('autoecole', function (Blueprint $table) {
            $table->id();
            $table->dateTime('inscription_time'); 
            $table->string('nin')->unique(); 
            $table->string('gender'); 
            $table->string('nom_ar'); 
            $table->string('prenom_ar'); 
            $table->string('nom_fr')->nullable(); 
            $table->string('prenom_fr')->nullable(); 
            $table->date('birthdate'); 
            $table->string('birthplace'); 
            $table->string('adresse'); 
            $table->string('phone')->unique(); 
            $table->string('email')->nullable();
            $table->string('type');
            $table->boolean('rejet')->default(false);
            $table->string('validation_number')->unique()->nullable();
            $table->string('payment_number')->unique()->nullable();
            $table->string('cheque_number')->nullable();
            $table->float('montant_paiement')->nullable();
            $table->date('date_paiement')->nullable();
            $table->unsignedBigInteger('session_id')->nullable();
            $table->unsignedBigInteger('list')->nullable();
            $table->json('notes')->nullable();
            $table->string('ip_adress')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('session_id')->references('id')->on('formation_sessions')->nullable();
            $table->foreign('list')->references('id')->on('autoecole_list')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('autoecole');
    }
};
