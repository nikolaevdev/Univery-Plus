<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_info', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');

            $table->string('name', 35);
            $table->string('surname', 35);
            $table->string('patronymic', 35)->nullable();
            $table->string('full', 100);

            $table->tinyInteger('sex');

            $table->string('checkword')->nullable();
            
            $table->tinyInteger('birthday')->nullable();
            $table->tinyInteger('birthmonth')->nullable();
            $table->smallInteger('birthyear')->nullable();

            $table->string('telephone', 20);
            $table->string('telephone2', 20)->nullable();

            $table->unsignedBigInteger('identification_document_id')->nullable();
            $table->smallInteger('identification_document_series')->nullable();
            $table->smallInteger('identification_document_number')->nullable();
            $table->integer('identification_division_code')->nullable();
            $table->string('identification_issued_by', 100)->nullable();
            $table->tinyInteger('identification_day')->nullable();
            $table->tinyInteger('identification_month')->nullable();
            $table->smallInteger('identification_year')->nullable();
            $table->text('identification_document_url')->nullable();

            $table->unsignedBigInteger('document_on_education_level_id')->nullable();
            $table->unsignedBigInteger('document_on_education_id')->nullable();
            $table->smallInteger('document_on_education_series')->nullable();
            $table->smallInteger('document_on_education_number')->nullable();

            $table->tinyInteger('document_on_education_day_of_issue')->nullable();
            $table->tinyInteger('document_on_education_month')->nullable();
            $table->smallInteger('document_on_education_year')->nullable();
            $table->text('document_on_education_name_of_organization')->nullable();
            $table->string('document_on_education_organization_region', 100)->nullable();
            $table->string('document_on_education_organization_city', 100)->nullable();
            $table->text('document_on_education_year_of_receipt')->nullable();
            $table->text('document_on_education_year_of_completion')->nullable();
            $table->text('document_on_education_file_url')->nullable();

            $table->text('сitizenship')->nullable();
            $table->string('сitizenship_region', 100)->nullable();
            $table->string('сitizenship_city', 100)->nullable();
            $table->string('сitizenship_street', 100)->nullable();
            $table->smallInteger('сitizenship_house')->nullable();
            $table->smallInteger('сitizenship_building')->nullable();
            $table->smallInteger('сitizenship_flat')->nullable();
            $table->integer('postalcode')->nullable();

            $table->string('сitizenship_region_actual', 100)->nullable();
            $table->string('сitizenship_city_actual', 100)->nullable();
            $table->string('сitizenship_street_actual', 100)->nullable();
            $table->smallInteger('сitizenship_house_actual')->nullable();
            $table->smallInteger('сitizenship_building_actual')->nullable();
            $table->smallInteger('сitizenship_flat_actual')->nullable();
            $table->integer('postalcode_actual')->nullable();

            $table->tinyInteger('liable_for_military_service')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('dormitories')->nullable();
            $table->tinyInteger('benefits')->nullable();
            $table->tinyInteger('individual_achievements')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('identification_document_id')->references('id')->on('identification_documents_name');
            $table->foreign('document_on_education_level_id')->references('id')->on('previous_education_level_name');
            $table->foreign('document_on_education_id')->references('id')->on('document_on_education_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_info');
    }
}
