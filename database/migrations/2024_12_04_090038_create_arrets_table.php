<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('arrets', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->decimal('lat', 10, 7);
            $table->decimal('long', 10, 7);
            $table->boolean('mahata')->default(false);
            $table->boolean('isfin')->default(false);
            $table->boolean('isinter')->default(false);
            $table->boolean('inactive')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arrets');
    }
};
