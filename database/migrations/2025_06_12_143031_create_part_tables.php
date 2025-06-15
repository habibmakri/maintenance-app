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

        Schema::create('entreprise', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        Schema::create('transport_personne', function (Blueprint $table) {
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
            $table->string('type_permis'); 
            $table->date('date_permis'); 
            $table->string('lieu_permis'); 
            $table->boolean('rejet')->default(false);
            $table->string('validation_number')->unique()->nullable();
            $table->string('payment_number')->unique()->nullable();
            $table->unsignedBigInteger('entreprise_id');
            $table->string('ip_adress');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('entreprise_id')->references('id')->on('entreprise')->nullable(); 
        });
        Schema::create('transport_marchandise', function (Blueprint $table) {
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
            $table->string('type_permis'); 
            $table->date('date_permis'); 
            $table->string('lieu_permis'); 
            $table->boolean('rejet')->default(false);
            $table->string('validation_number')->unique()->nullable();
            $table->string('payment_number')->unique()->nullable();
            $table->unsignedBigInteger('entreprise_id');
            $table->string('ip_adress');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('entreprise_id')->references('id')->on('entreprise')->nullable(); 
        });
        Schema::create('transport_danger', function (Blueprint $table) {
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
            $table->string('type_permis'); 
            $table->date('date_permis'); 
            $table->string('lieu_permis'); 
            $table->boolean('rejet')->default(false);
            $table->string('validation_number')->unique()->nullable();
            $table->string('payment_number')->unique()->nullable();
            $table->unsignedBigInteger('entreprise_id');
            $table->string('ip_adress');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('entreprise_id')->references('id')->on('entreprise')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('part_tables');
    }
};
