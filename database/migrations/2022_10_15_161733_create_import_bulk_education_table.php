<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportBulkEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_bulk_education', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('business_id');
            $table->bigInteger('parent_id')->nullable();
            $table->string('unique_id',150)->nullable();
            $table->bigInteger('sla_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->string('client_display_id',150)->nullable();
            $table->string('client_emp_code',150)->nullable();
            $table->string('entity_code',100)->nullable();
            $table->string('name',255)->nullable();
            $table->string('first_name',150)->nullable();
            $table->string('middle_name',100)->nullable();
            $table->string('last_name',150)->nullable();
            $table->string('father_name',150)->nullable();
            $table->string('aadhar_number',100);
            $table->date('dob')->nullable();
            $table->string('gender',20)->nullable();
            $table->string('phone',40)->nullable();
            $table->string('email',255)->nullable();
            $table->integer('no_of_checks')->nullable();
            $table->string('price',255)->nullable();
            $table->string('university_board_name_1',255)->nullable();
            $table->string('degree_1',255)->nullable();
            $table->string('registration_enroll_1',255)->nullable();
            $table->string('year_of_qualification_1',255)->nullable();
            $table->string('university_board_name_2',255)->nullable();
            $table->string('degree_2',255)->nullable();
            $table->string('registration_enroll_2',255)->nullable();
            $table->string('year_of_qualification_2',255)->nullable();
            $table->string('university_board_name_3',255)->nullable();
            $table->string('degree_3',255)->nullable();
            $table->string('registration_enroll_3',255)->nullable();
            $table->string('year_of_qualification_3',255)->nullable();
            $table->string('university_board_name_4',255)->nullable();
            $table->string('degree_4',255)->nullable();
            $table->string('registration_enroll_4',255)->nullable();
            $table->string('year_of_qualification_4',255)->nullable();
            $table->string('university_board_name_5',255)->nullable();
            $table->string('degree_5',255)->nullable();
            $table->string('registration_enroll_5',255)->nullable();
            $table->string('year_of_qualification_5',255)->nullable();
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
        Schema::dropIfExists('import_bulk_education');
    }
}
