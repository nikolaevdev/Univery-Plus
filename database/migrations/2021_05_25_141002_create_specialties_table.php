<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specialties', function (Blueprint $table) {
            $table->id();
            $table->string('name', 35);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('faculty')->nullable();
            $table->string('direction_code', 20)->nullable();
            $table->string('duration_of_training', 20)->nullable();
            $table->integer('postalcode_actual')->nullable();
            $table->tinyInteger('full_time')->nullable();
            $table->tinyInteger('full_time_correspondence_course')->nullable();
            $table->tinyInteger('correspondence_course')->nullable();
            $table->tinyInteger('correspondence_course_distance')->nullable();
            $table->timestamps();

            $table->foreign('faculty')->references('id')->on('faculties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specialties');
    }
}
