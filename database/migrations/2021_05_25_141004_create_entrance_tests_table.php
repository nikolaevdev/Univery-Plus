<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntranceTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entrance_tests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 35);
            $table->unsignedBigInteger('specialization')->nullable();
            $table->timestamps();

            $table->foreign('specialization')->references('id')->on('specialties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entrance_tests');
    }
}
