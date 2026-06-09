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
        Schema::create('gasoile_cartes', function (Blueprint $table) {
            $table->id();
            $table->integer("number");
            $table->string("name");
            $table->float("initial_balance");
            $table->float("actual_balance");
            $table->boolean("state");
            $table->timestamps();
        });
        Schema::create('gasoile_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_carte");
            $table->boolean("recharge");
            $table->string("chauffeur")->nullable();
            $table->boolean("mission")->default(false);
            $table->string("place")->nullable();
            $table->dateTime("date");
            $table->float("quantite");
            $table->timestamps();
            $table->foreign('id_carte')->references('id')->on('gasoile_cartes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gasoile_transactions');
        Schema::dropIfExists('gasoile_cartes');
    }
};
