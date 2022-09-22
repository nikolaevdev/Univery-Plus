<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications_data', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('application_id')->nullable();
            $table->unsignedBigInteger('specialty_id')->nullable();
            $table->text('statement_file_url')->nullable();
            
            $table->foreign('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreign('specialty_id')->references('id')->on('specialties')->onDelete('cascade');
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
        Schema::dropIfExists('applications_data');
    }
}
