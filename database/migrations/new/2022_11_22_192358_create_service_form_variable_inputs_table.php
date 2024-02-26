<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceFormVariableInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service_form_variable_inputs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('service_id')->index()->unsigned()->nullable();
            $table->bigInteger('form_input_type_id')->index()->unsigned()->nullable();
            $table->bigInteger('service_form_input_id')->index()->unsigned()->nullable();
            $table->string('label_name',100)->nullable();
            $table->tinyInteger('is_report_output')->default('0');
            $table->tinyInteger('is_executive_summary')->default('0');
            $table->tinyInteger('is_mandatory')->default('0');
            $table->enum('status',['0','1'])->default('1');
            $table->integer('created_by')->unsigned()->index()->nullable();
            $table->integer('updated_by')->unsigned()->index()->nullable();
            $table->integer('is_deleted')->nullable();
            $table->integer('deleted_by')->unsigned()->index()->nullable();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('service_form_variable_inputs');
    }
}
