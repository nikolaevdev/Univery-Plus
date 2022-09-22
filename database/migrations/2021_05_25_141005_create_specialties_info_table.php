<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialtiesInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialties_info', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('specialty');
            $table->unsignedBigInteger('use');

            $table->timestamps();

            $table->foreign('specialty')->references('id')->on('specialties')->onDelete('cascade');
            $table->foreign('use')->references('id')->on('use');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialties_info');
    }
}
