<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCustomCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_custom_candidates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->index()->unsigned()->nullable();
            $table->bigInteger('business_id')->index()->unsigned()->nullable();
            $table->string('unique_id',150)->index()->nullable();
            $table->bigInteger('sla_id')->index()->nullable();
            $table->string('client_emp_code',150)->nullable();
            $table->string('name',255)->nullable();
            $table->string('first_name',150)->nullable();
            $table->string('middle_name',150)->nullable();
            $table->string('last_name',150)->nullable();
            $table->string('father_name',150)->nullable();
            $table->date('dob')->nullable();
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('document_type',150)->nullable();
            $table->string('document_number',150)->nullable();
            $table->string('email',200)->nullable();
            $table->string('phone',40)->nullable();
            $table->date('doj')->nullable();
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
        Schema::dropIfExists('import_custom_candidates');
    }
}
