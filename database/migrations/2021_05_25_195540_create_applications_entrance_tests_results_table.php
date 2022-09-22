<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsEntranceTestsResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications_entrance_tests_results', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('entrance_tests')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->dateTime('time_event', $precision = 0);

            $table->timestamps();

            $table->foreign('entrance_tests')->references('id')->on('entrance_tests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications_entrance_tests_results');
    }
}
