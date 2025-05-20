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
        Schema::create('taxis_prov_list', function (Blueprint $table) {
            $table->id();
            $table->integer('counter')->default(0); 
            $table->date('valid_date')->nullable(); 
            $table->timestamps();
        });
        Schema::create('taxis_prov', function (Blueprint $table) {
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
            $table->string('n_permis'); 
            $table->date('date_permis'); 
            $table->string('lieu_permis'); 
            $table->string('comune_exploi'); 
            $table->boolean('rejet')->default(false);
            $table->string('validation_number')->unique()->nullable();
            $table->unsignedBigInteger('list');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('list')->references('id')->on('taxis_prov_list')->nullable(); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxis_prov');
        Schema::dropIfExists('taxis_prov_list');
    }
};
