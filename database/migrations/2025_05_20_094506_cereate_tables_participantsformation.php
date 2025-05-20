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
        Schema::create('taxis', function (Blueprint $table) {
            $table->id();
            $table->string('nom_ar'); 
            $table->string('prenom_ar'); 
            $table->string('nom_fr'); 
            $table->string('prenom_fr'); 
            $table->date('birthdate'); 
            $table->string('birthplace'); 
            $table->string('adresse'); 
            $table->string('dtw'); 
            $table->string('phone')->unique(); 
            $table->string('email')->nullable(); 
            $table->string('n_permis'); 
            $table->date('date_permis'); 
            $table->string('lieu_permis'); 
            $table->boolean('rejet')->default(false);
            // $table->string('list')->nullable();
            $table->string('validation_number')->unique()->nullable(); 
            $table->string('payment_number')->unique()->nullable();
            $table->softDeletes();
            $table->timestamps();
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
