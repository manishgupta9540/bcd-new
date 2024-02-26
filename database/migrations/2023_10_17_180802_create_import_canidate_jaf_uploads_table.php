<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportCanidateJafUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_canidate_jaf_uploads', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('parent_id')->unsigned()->index()->nullable();
            $table->bigInteger('business_id')->unsigned()->index()->nullable();
            $table->string('unique_id',255)->nullable();
            $table->bigInteger('sla_id')->unsigned()->index()->nullable();
            $table->string('service_id')->nullable();
            $table->string('client_emp_code',255)->nullable();
            $table->string('entity_code',255)->nullable();
            $table->string('name',255)->nullable();
            $table->string('first_name',255)->nullable();
            $table->string('middle_name',255)->nullable();
            $table->string('last_name',255)->nullable();
            $table->string('father_name',255)->nullable();
            $table->string('aadhaar_number',255)->nullable();
            $table->string('dob',255)->nullable();
            $table->string('gender',255)->nullable();
            $table->string('phone',255)->nullable();
            $table->string('email',255)->nullable();
            $table->string('jaf_filling_access',255)->nullable();
            $table->longtext('jaf_form_data')->nullable();
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
        Schema::dropIfExists('import_canidate_jaf_uploads');
    }
}
