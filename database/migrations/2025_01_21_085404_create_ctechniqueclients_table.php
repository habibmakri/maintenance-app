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
        Schema::create('ctechniqueclients', function (Blueprint $table) {
            $table->id();
            $table->date('date_controle');
            $table->string('name');
            $table->unsignedBigInteger('type_id');
            $table->string('immatriculation');
            $table->string('phone');
            $table->date('last_remind');
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('ctechniqueclienttypes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctechniqueclients');
    }
};
